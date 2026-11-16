export type Locale = 'id' | 'en';

export interface SocialLinks {
  linkedin?: string;
  github?: string;
  instagram?: string;
  website?: string;
}

export interface Profile {
  id: string;
  full_name: string;
  headline: string | null;
  intro: string | null;
  about: string | null;
  location: string | null;
  email: string | null;
  phone: string | null;
  availability: string | null;
  is_available: boolean;
  avatar_url: string | null;
  cv_url: string | null;
  socials: SocialLinks;
  stats: {
    years_learning: number;
    projects_completed: number;
    tools_mastered: number;
  };
}

export interface Skill {
  id: string;
  name: string;
  category: 'hard' | 'soft';
  percentage: number;
  description: string | null;
  icon: string | null;
}

export interface Education {
  id: string;
  institution: string;
  degree: string | null;
  field: string | null;
  start_date: string;
  end_date: string | null;
  period: string;
  description: string | null;
}

export interface Certificate {
  id: string;
  title: string;
  issuer: string;
  issued_at: string | null;
  expires_at: string | null;
  credential_id: string | null;
  credential_url: string | null;
  image_url: string | null;
  description: string | null;
}

export type ExperienceType =
  | 'work'
  | 'organization'
  | 'competition'
  | 'internship'
  | 'education'
  | string;

export interface Experience {
  id: string;
  type: ExperienceType;
  type_label: string;
  title: string;
  company: string;
  location: string | null;
  date_range: string;
  description: string | null;
  tags: string[];
  url: string | null;
}

export interface Project {
  id: string;
  slug: string;
  title: string;
  excerpt: string | null;
  description: string | null;
  category: string | null;
  category_label: string | null;
  tags: string[];
  url: string | null;
  repo_url: string | null;
  image_url: string | null;
  status: 'draft' | 'published' | 'coming_soon' | string;
  is_featured: boolean;
  content?: string | null;
  gallery?: string[];
  created_at?: string | null;
}

export interface Testimonial {
  id: string;
  name: string;
  role: string | null;
  company: string | null;
  quote: string;
  category: 'client' | 'mentor' | 'teacher' | 'peer' | string;
  category_label: string;
  avatar_url: string | null;
  rating: number;
}

export interface Post {
  id: string;
  slug: string;
  title: string;
  excerpt: string | null;
  cover_url: string | null;
  category: string | null;
  tags: string[];
  published_at: string | null;
  is_featured: boolean;
  reading_time: number;
  body?: string | null;
}

export interface CategoryOption {
  value: string;
  label: string;
  count?: number;
}

export interface Portfolio {
  locale: Locale;
  generated_at: string;
  profile: Profile;
  skills: {
    hard: Skill[];
    soft: Skill[];
  };
  educations: Education[];
  certificates: Certificate[];
  experiences: Experience[];
  projects: Project[];
  project_categories: CategoryOption[];
  testimonials: Testimonial[];
  testimonial_categories: CategoryOption[];
  posts: Post[];
}

export interface GuestbookEntry {
  id: string;
  name: string;
  message: string;
  created_at: string | null;
}

export interface GuestbookResponse {
  data: GuestbookEntry[];
  meta: {
    total: number;
  };
}
