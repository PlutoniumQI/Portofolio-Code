<?php

namespace App\Filesystem;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use League\Flysystem\Config;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToWriteFile;
use League\MimeTypeDetection\ExtensionMimeTypeDetector;

/**
 * Minimal Flysystem adapter for the GitHub Contents API.
 *
 * Uploads are committed to a public repository, which gives the free Render
 * instance durable storage for images and PDFs. Only the operations Filament
 * needs (write, read, exists, delete, list, url) are implemented; directory
 * creation is a no-op because Git does not track empty directories.
 */
class GitHubAdapter implements FilesystemAdapter
{
    public function __construct(
        private readonly string $token,
        private readonly string $owner,
        private readonly string $repo,
        private readonly string $branch = 'main',
        private readonly string $root = '',
        private readonly string $apiUrl = 'https://api.github.com',
        private readonly string $publicUrl = '',
    ) {}

    public function fileExists(string $path): bool
    {
        $metadata = $this->metadata($this->fullPath($path));

        return $metadata !== null && ! array_is_list($metadata);
    }

    public function directoryExists(string $path): bool
    {
        if ($this->fullPath($path) === '') {
            return true;
        }

        $metadata = $this->metadata($this->fullPath($path));

        return $metadata !== null && array_is_list($metadata);
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $fullPath = $this->fullPath($path);
        $existing = $this->metadata($fullPath);

        if ($existing !== null && array_is_list($existing)) {
            throw UnableToWriteFile::atLocation($path, 'A directory already exists at this path.');
        }

        $payload = [
            'message' => "Upload {$fullPath}",
            'content' => base64_encode($contents),
            'branch' => $this->branch,
        ];

        if (is_array($existing) && isset($existing['sha'])) {
            $payload['sha'] = $existing['sha'];
        }

        $response = $this->client()->put($this->contentsEndpoint($fullPath), $payload);

        if ($response->failed()) {
            throw UnableToWriteFile::atLocation($path, $response->body());
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $contents = stream_get_contents($contents);

        if ($contents === false) {
            throw UnableToWriteFile::atLocation($path, 'Unable to read the uploaded stream.');
        }

        $this->write($path, $contents, $config);
    }

    public function read(string $path): string
    {
        $response = $this->client()
            ->withHeaders(['Accept' => 'application/vnd.github.raw'])
            ->get($this->contentsEndpoint($this->fullPath($path)), ['ref' => $this->branch]);

        if ($response->failed()) {
            throw UnableToReadFile::fromLocation($path, $response->body());
        }

        return $response->body();
    }

    public function readStream(string $path)
    {
        $stream = fopen('php://temp', 'r+');

        if ($stream === false) {
            throw UnableToReadFile::fromLocation($path, 'Unable to create a temporary stream.');
        }

        fwrite($stream, $this->read($path));
        rewind($stream);

        return $stream;
    }

    public function delete(string $path): void
    {
        $fullPath = $this->fullPath($path);
        $metadata = $this->metadata($fullPath);

        if ($metadata === null) {
            return;
        }

        if (array_is_list($metadata)) {
            throw UnableToDeleteFile::atLocation($path, 'The path is a directory.');
        }

        $response = $this->client()->delete($this->contentsEndpoint($fullPath), [
            'message' => "Delete {$fullPath}",
            'sha' => $metadata['sha'],
            'branch' => $this->branch,
        ]);

        if ($response->failed()) {
            throw UnableToDeleteFile::atLocation($path, $response->body());
        }
    }

    public function deleteDirectory(string $path): void
    {
        foreach ($this->listContents($path, true) as $item) {
            if ($item->isFile()) {
                $this->delete($item->path());
            }
        }
    }

    public function createDirectory(string $path, Config $config): void
    {
        // Git cannot represent an empty directory. A directory is created
        // implicitly when the first file inside it is committed.
    }

    public function setVisibility(string $path, string $visibility): void
    {
        // The assets repository is public; GitHub has no per-file visibility.
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($this->relativePath($this->fullPath($path)), visibility: 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        $metadata = $this->fileMetadata($path);

        return new FileAttributes(
            $this->relativePath($metadata['path']),
            $metadata['size'] ?? null,
            'public',
            $this->timestamp($metadata),
            (new ExtensionMimeTypeDetector)->detectMimeType($path) ?? 'application/octet-stream',
        );
    }

    public function lastModified(string $path): FileAttributes
    {
        $metadata = $this->fileMetadata($path);

        return new FileAttributes(
            $this->relativePath($metadata['path']),
            $metadata['size'] ?? null,
            'public',
            $this->timestamp($metadata),
        );
    }

    public function fileSize(string $path): FileAttributes
    {
        $metadata = $this->fileMetadata($path);

        return new FileAttributes(
            $this->relativePath($metadata['path']),
            $metadata['size'] ?? null,
            'public',
            $this->timestamp($metadata),
        );
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $fullPath = $this->fullPath($path);
        $metadata = $this->metadata($fullPath);

        if ($metadata === null) {
            return;
        }

        if (! array_is_list($metadata)) {
            yield $this->fileAttributes($metadata);

            return;
        }

        foreach ($metadata as $item) {
            if (($item['type'] ?? null) === 'dir') {
                yield new DirectoryAttributes($this->relativePath($item['path']), 'public', $this->timestamp($item));

                if ($deep) {
                    yield from $this->listContents($this->relativePath($item['path']), true);
                }

                continue;
            }

            yield $this->fileAttributes($item);
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        try {
            $this->copy($source, $destination, $config);
            $this->delete($source);
        } catch (\Throwable $exception) {
            throw UnableToMoveFile::fromLocationTo($source, $destination, $exception);
        }
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        try {
            $this->write($destination, $this->read($source), $config);
        } catch (\Throwable $exception) {
            throw UnableToCopyFile::fromLocationTo($source, $destination, $exception);
        }
    }

    /**
     * Laravel's FilesystemAdapter uses this method when it exists, so
     * Storage::disk('github')->url($path) returns the raw GitHub URL.
     */
    public function getUrl(string $path): string
    {
        $path = ltrim(str_replace(' ', '%20', $path), '/');

        if (filled($this->publicUrl)) {
            return rtrim($this->publicUrl, '/').'/'.$path;
        }

        $base = sprintf(
            'https://raw.githubusercontent.com/%s/%s/%s',
            $this->owner,
            $this->repo,
            $this->branch,
        );

        if ($this->root !== '') {
            $base .= '/'.trim($this->root, '/');
        }

        return $base.'/'.$path;
    }

    public function getTemporaryUrl(string $path, \DateTimeInterface $expiration, array $options = []): string
    {
        return $this->getUrl($path);
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(rtrim($this->apiUrl, '/'))
            ->withToken($this->token)
            ->accept('application/vnd.github+json')
            ->withHeaders([
                'X-GitHub-Api-Version' => '2022-11-28',
                'User-Agent' => 'portfolio-api',
            ])
            ->timeout(30);
    }

    /**
     * @return array<int|string, mixed>|null
     */
    private function metadata(string $fullPath): ?array
    {
        $response = $this->client()->get($this->contentsEndpoint($fullPath), [
            'ref' => $this->branch,
        ]);

        if ($response->status() === 404) {
            return null;
        }

        if ($response->failed()) {
            throw UnableToRetrieveMetadata::create($fullPath, 'file', $response->body());
        }

        $data = $response->json();

        return is_array($data) ? $data : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function fileMetadata(string $path): array
    {
        $metadata = $this->metadata($this->fullPath($path));

        if ($metadata === null || array_is_list($metadata)) {
            throw UnableToRetrieveMetadata::create($path, 'file');
        }

        return $metadata;
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function fileAttributes(array $metadata): FileAttributes
    {
        return new FileAttributes(
            $this->relativePath($metadata['path']),
            $metadata['size'] ?? null,
            'public',
            $this->timestamp($metadata),
            isset($metadata['name']) ? (new ExtensionMimeTypeDetector)->detectMimeType($metadata['name']) : null,
        );
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function timestamp(array $metadata): int
    {
        if (isset($metadata['updated_at'])) {
            return strtotime((string) $metadata['updated_at']) ?: time();
        }

        return time();
    }

    private function fullPath(string $path): string
    {
        $path = trim($path, '/');
        $root = trim($this->root, '/');

        if ($root === '') {
            return $path;
        }

        return $path === '' ? $root : "{$root}/{$path}";
    }

    private function relativePath(string $fullPath): string
    {
        $root = trim($this->root, '/');

        if ($root === '') {
            return ltrim($fullPath, '/');
        }

        if ($fullPath === $root) {
            return '';
        }

        return ltrim(str_starts_with($fullPath, "{$root}/") ? substr($fullPath, strlen($root) + 1) : $fullPath, '/');
    }

    private function contentsEndpoint(string $fullPath): string
    {
        $encodedPath = implode('/', array_map('rawurlencode', array_filter(explode('/', $fullPath), fn (string $segment): bool => $segment !== '')));
        $endpoint = sprintf('/repos/%s/%s/contents', rawurlencode($this->owner), rawurlencode($this->repo));

        return $encodedPath === '' ? $endpoint : "{$endpoint}/{$encodedPath}";
    }
}
