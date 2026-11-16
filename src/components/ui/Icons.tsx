import type { SVGProps } from 'react';

export type IconName =
  | 'arrow-right'
  | 'arrow-up-right'
  | 'arrow-left'
  | 'arrow-up'
  | 'chevron-down'
  | 'external'
  | 'github'
  | 'linkedin'
  | 'instagram'
  | 'mail'
  | 'phone'
  | 'map-pin'
  | 'search'
  | 'close'
  | 'menu'
  | 'sun'
  | 'moon'
  | 'globe'
  | 'calendar'
  | 'check'
  | 'download'
  | 'printer'
  | 'quote'
  | 'star'
  | 'sparkles'
  | 'code'
  | 'palette'
  | 'figma'
  | 'layers'
  | 'briefcase'
  | 'academic-cap'
  | 'trophy'
  | 'users'
  | 'chat'
  | 'clock'
  | 'refresh'
  | 'book'
  | 'document'
  | 'home'
  | 'user'
  | 'heart'
  | 'message'
  | 'send'
  | 'filter'
  | 'grid';

type IconDefinition = {
  fill?: boolean;
  content: React.ReactNode;
};

const icons: Record<IconName, IconDefinition> = {
  'arrow-right': {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.6} d="M4 12h16m0 0-6-6m6 6-6 6" />,
  },
  'arrow-up-right': {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.6} d="M7 17 17 7M9 7h8v8" />,
  },
  'arrow-left': {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.6} d="M20 12H4m0 0 6-6m-6 6 6 6" />,
  },
  'arrow-up': {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.8} d="m5 15 7-7 7 7" />,
  },
  'chevron-down': {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.8} d="m6 9 6 6 6-6" />,
  },
  external: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.6}
        d="M14 4h6m0 0v6m0-6-9 9M9 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-3"
      />
    ),
  },
  github: {
    fill: true,
    content: (
      <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0 0 22 12.017C22 6.484 17.522 2 12 2Z" />
    ),
  },
  linkedin: {
    fill: true,
    content: (
      <path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05a3.74 3.74 0 0 1 3.37-1.85c3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.12 20.45H3.56V9h3.56v11.45Z" />
    ),
  },
  instagram: {
    fill: true,
    content: (
      <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069Zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073Zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324Zm0 10.162a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881Z" />
    ),
  },
  mail: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M3 6.75A1.75 1.75 0 0 1 4.75 5h14.5A1.75 1.75 0 0 1 21 6.75v10.5A1.75 1.75 0 0 1 19.25 19H4.75A1.75 1.75 0 0 1 3 17.25V6.75Zm0 0L12 13l9-6.25"
      />
    ),
  },
  phone: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a.75.75 0 0 1-.93.25 15.14 15.14 0 0 1-6.674-6.674.75.75 0 0 1 .25-.93l1.293-.97c.36-.27.508-.744.417-1.173L7.628 4.602A1.125 1.125 0 0 0 6.537 3.75H5.25A2.25 2.25 0 0 0 3 6v.75Z"
      />
    ),
  },
  'map-pin': {
    content: (
      <>
        <path
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth={1.5}
          d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
        />
        <path
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth={1.5}
          d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"
        />
      </>
    ),
  },
  search: {
    content: (
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.7} d="m21 21-4.35-4.35m2.35-5.4a7.75 7.75 0 1 1-15.5 0 7.75 7.75 0 0 1 15.5 0Z" />
    ),
  },
  close: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.8} d="M6 18 18 6M6 6l12 12" />,
  },
  menu: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.8} d="M4 7h16M4 12h16M4 17h16" />,
  },
  sun: {
    content: (
      <>
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 3v2.25m0 13.5V21M4.22 4.22l1.59 1.59m12.38 12.38 1.59 1.59M3 12h2.25m13.5 0H21M4.22 19.78l1.59-1.59M18.19 5.81l1.59-1.59" />
        <circle cx="12" cy="12" r="4.5" strokeWidth={1.5} />
      </>
    ),
  },
  moon: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />,
  },
  globe: {
    content: (
      <>
        <circle cx="12" cy="12" r="9" strokeWidth={1.5} />
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M3.6 9h16.8M3.6 15h16.8M12 3c2.2 2.5 3.3 5.5 3.3 9s-1.1 6.5-3.3 9c-2.2-2.5-3.3-5.5-3.3-9S9.8 5.5 12 3Z" />
      </>
    ),
  },
  calendar: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M6.75 3v2.25M17.25 3v2.25M3.75 18V7.5a1.5 1.5 0 0 1 1.5-1.5h13.5a1.5 1.5 0 0 1 1.5 1.5V18a1.5 1.5 0 0 1-1.5 1.5H5.25A1.5 1.5 0 0 1 3.75 18Zm0-7.5h16.5"
      />
    ),
  },
  check: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m5 12 4.5 4.5L19 7" />,
  },
  download: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.6} d="M12 3v12m0 0 4.5-4.5M12 15l-4.5-4.5M4 17.25V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1.75" />,
  },
  printer: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M6.75 8.25V3.75h10.5v4.5M6 18.75H4.5A1.5 1.5 0 0 1 3 17.25V10.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 10.5v6.75a1.5 1.5 0 0 1-1.5 1.5H18M6.75 14.25h10.5v6H6.75v-6Z"
      />
    ),
  },
  quote: {
    fill: true,
    content: <path d="M9.5 6C6.46 6 4 8.46 4 11.5c0 2.9 2.2 5.28 5 5.5-.4 1.4-1.5 2.3-3 2.5v1.5c3.9-.2 6.5-3.4 6.5-7.5V11.5C12.5 8.46 10.54 6 9.5 6Zm10 0C16.46 6 14 8.46 14 11.5c0 2.9 2.2 5.28 5 5.5-.4 1.4-1.5 2.3-3 2.5v1.5c3.9-.2 6.5-3.4 6.5-7.5V11.5C22.5 8.46 20.54 6 19.5 6Z" />,
  },
  star: {
    fill: true,
    content: <path d="m12 2.5 2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.3l-5.8 3.06 1.11-6.46-4.7-4.58 6.49-.94L12 2.5Z" />,
  },
  sparkles: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.4}
        d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"
      />
    ),
  },
  code: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.6} d="m8 8-4 4 4 4m8-8 4 4-4 4m-2.5-11-3 14" />,
  },
  palette: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M12 3a9 9 0 0 0 0 18c1.38 0 2.25-.9 2.25-2.1 0-.58-.22-1.1-.58-1.5a2.1 2.1 0 0 1 1.55-3.53H17A4 4 0 0 0 21 9.9C21 5.98 16.97 3 12 3Z"
      />
    ),
  },
  figma: {
    fill: true,
    content: (
      <>
        <path d="M8 24c2.208 0 4-1.792 4-4v-4H8c-2.208 0-4 1.792-4 4s1.792 4 4 4Z" />
        <path d="M4 16c0-2.208 1.792-4 4-4h4v8H8c-2.208 0-4-1.792-4-4Z" opacity=".85" />
        <path d="M4 8c0-2.208 1.792-4 4-4h4v8H8c-2.208 0-4-1.792-4-4Z" opacity=".7" />
        <path d="M12 4h4a4 4 0 0 1 0 8h-4V4Z" opacity=".55" />
        <path d="M12 12h4a4 4 0 1 1-4 4v-4Z" opacity=".4" />
      </>
    ),
  },
  layers: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="m12 3 9 5-9 5-9-5 9-5Zm9 9-9 5-9-5m18 4-9 5-9-5" />,
  },
  briefcase: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M9 7.5V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1.5M3.75 7.5h16.5A.75.75 0 0 1 21 8.25v10.5a.75.75 0 0 1-.75.75H3.75a.75.75 0 0 1-.75-.75V8.25a.75.75 0 0 1 .75-.75Zm0 5.25h16.5"
      />
    ),
  },
  'academic-cap': {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4.26 10.147a60.44 60.44 0 0 0-.491 6.347A48.63 48.63 0 0 1 12 20.904a48.63 48.63 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.9 59.9 0 0 1 12 3.493a59.9 59.9 0 0 1 10.399 5.84 51.3 51.3 0 0 0-2.658.814m-15.482 0A50.7 50.7 0 0 1 12 13.489a50.7 50.7 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.4 55.4 0 0 1 12 8.443m-7.007 11.55A5.98 5.98 0 0 0 6.75 15.75v-1.5" />,
  },
  trophy: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M16.5 3.75h3a.75.75 0 0 1 .75.75c0 2.9-1.63 5.25-4.5 5.7M7.5 3.75h-3a.75.75 0 0 0-.75.75c0 2.9 1.63 5.25 4.5 5.7M7.5 3.75h9v4.5a4.5 4.5 0 1 1-9 0v-4.5ZM12 12.75V17.25m-3.75 3h7.5m-6-3h4.5"
      />
    ),
  },
  users: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.34 9.34 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.32 12.32 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"
      />
    ),
  },
  chat: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM21 12c0 4.556-4.03 8.25-9 8.25a9.76 9.76 0 0 1-2.555-.337A5.97 5.97 0 0 1 5.41 20.97a5.97 5.97 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"
      />
    ),
  },
  clock: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />,
  },
  refresh: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M16.023 9.348h4.992V4.356m-4.992 4.992A7.5 7.5 0 0 0 5.11 6.34L3 8.25m17.016 8.402h-4.992v4.992m4.992-4.992A7.5 7.5 0 0 1 5.11 17.66L3 15.75" />,
  },
  book: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c1.75 0 3.384.503 4.773 1.375M12 6.042c0-.43.03-.854.088-1.27A8.967 8.967 0 0 1 18 3.75c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-1.75 0-3.384.503-4.773 1.375M12 6.042v13.333"
      />
    ),
  },
  document: {
    content: (
      <path
        strokeLinecap="round"
        strokeLinejoin="round"
        strokeWidth={1.5}
        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H6.75A2.25 2.25 0 0 0 4.5 4.5v15a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-1.5m0-3.75L12.75 21m6.75-6.75h-4.5"
      />
    ),
  },
  home: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="m2.25 12 8.954-8.955a1.125 1.125 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />,
  },
  user: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.93 17.93 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />,
  },
  heart: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />,
  },
  message: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />,
  },
  send: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />,
  },
  filter: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />,
  },
  grid: {
    content: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />,
  },
};

interface IconProps extends Omit<SVGProps<SVGSVGElement>, 'name'> {
  name: IconName;
  size?: number | string;
}

export function Icon({ name, size = 20, className, ...props }: IconProps) {
  const icon = icons[name];
  const isFilled = icon.fill === true;

  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      width={size}
      height={size}
      className={className}
      fill={isFilled ? 'currentColor' : 'none'}
      stroke={isFilled ? 'none' : 'currentColor'}
      aria-hidden="true"
      focusable="false"
      {...props}
    >
      {icon.content}
    </svg>
  );
}
