import { createClient } from 'contentful';

// Initialize the Contentful client using environment variables
const client = createClient({
  space: process.env.CONTENTFUL_SPACE_ID || 'your_space_id',
  accessToken: process.env.CONTENTFUL_ACCESS_TOKEN || 'your_access_token',
});

export interface Project {
  id: string;
  title: string;
  description: string;
  tags: string[];
  url: string;
  imageUrl: string;
  status: string;
}

// A helper function to fetch portfolio projects from the CMS
export async function getProjects(): Promise<Project[]> {
  try {
    // Fetches all entries matching the 'portfolioProject' content type
    const response = await client.getEntries({
      content_type: 'portfolioProject',
      order: ['-sys.createdAt'],
    });

    return response.items.map((item: any) => ({
      id: item.sys.id,
      title: (item.fields.title as string) || '',
      description: (item.fields.description as string) || '',
      tags: (item.fields.tags as string[]) || [],
      url: (item.fields.url as string) || '',
      imageUrl: (item.fields.imageUrl as string) || '',
      status: (item.fields.status as string) || 'live',
    }));
  } catch (error) {
    console.error('Error fetching projects from Contentful:', error);
    return [];
  }
}
