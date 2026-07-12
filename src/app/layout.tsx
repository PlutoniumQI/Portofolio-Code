import type { Metadata } from 'next';
import { Inter, JetBrains_Mono } from 'next/font/google';
import './globals.css';

const inter = Inter({
  subsets: ['latin'],
  weight: ['300', '400', '500', '600', '700'],
  variable: '--font-inter',
  display: 'swap',
});

const jetbrainsMono = JetBrains_Mono({
  subsets: ['latin'],
  weight: ['400', '500'],
  variable: '--font-jetbrains',
  display: 'swap',
});

export const metadata: Metadata = {
  title: 'Portfolio Re:SKY',
  description:
    'Muhammad Resky Ramadhan — UI/UX Designer & Front-end Developer based in Banjarmasin, Indonesia.',
  authors: [{ name: 'Muhammad Resky Ramadhan' }],
  openGraph: {
    title: 'Portfolio Re:SKY',
    description: 'Muhammad Resky Ramadhan — UI/UX Designer & Front-end Developer.',
    type: 'website',
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" className={`scroll-smooth ${inter.variable} ${jetbrainsMono.variable}`}>
      <body className="bg-zinc-950 text-zinc-300 font-sans antialiased">
        {children}
      </body>
    </html>
  );
}
