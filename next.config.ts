import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  async redirects() {
    return [
      // The site lives under /id and /en. The root redirects to Indonesian.
      { source: '/', destination: '/id', permanent: false },
    ];
  },
};

export default nextConfig;
