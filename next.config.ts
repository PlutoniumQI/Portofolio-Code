import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  async redirects() {
    return [
      // The site lives under /id and /en. The root redirects to Indonesian.
      { source: '/', destination: '/id', permanent: false },
      // The guestbook was retired in favour of moderated testimonials.
      { source: '/guestbook', destination: '/id/testimonials', permanent: true },
      { source: '/id/guestbook', destination: '/id/testimonials', permanent: true },
      { source: '/en/guestbook', destination: '/en/testimonials', permanent: true },
    ];
  },
};

export default nextConfig;
