const { createClient } = require('contentful-management');
const fs = require('fs');
const path = require('path');
const readline = require('readline');

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const askQuestion = (query) => new Promise((resolve) => rl.question(query, resolve));

async function main() {
  console.log('\n🚀 Contentful Automatic Setup & Seeding Script\n');
  
  // 1. Read existing env variables if any
  let spaceId = process.env.CONTENTFUL_SPACE_ID;
  let managementToken = process.env.CONTENTFUL_MANAGEMENT_TOKEN;

  const envPath = path.join(process.cwd(), '.env.local');
  if (fs.existsSync(envPath)) {
    const envContent = fs.readFileSync(envPath, 'utf8');
    const spaceMatch = envContent.match(/^CONTENTFUL_SPACE_ID=(.+)$/m);
    if (spaceMatch && spaceMatch[1] && !spaceMatch[1].includes('your_space_id')) {
      spaceId = spaceMatch[1].trim();
    }
  }

  // 2. Prompt for inputs if not available
  if (!spaceId) {
    spaceId = await askQuestion('👉 Enter your Contentful Space ID: ');
    spaceId = spaceId.trim();
  } else {
    console.log(`✅ Using Space ID: ${spaceId}`);
  }

  if (!managementToken) {
    console.log('\nTo generate a Personal Access Token (Management Token):');
    console.log('1. Log in to Contentful (https://app.contentful.com)');
    console.log('2. Go to Settings → API keys');
    console.log('3. Click "Personal Access Tokens" and generate one.\n');
    managementToken = await askQuestion('👉 Enter your Contentful Personal Access Token (CMA): ');
    managementToken = managementToken.trim();
  }

  if (!spaceId || !managementToken) {
    console.error('❌ Error: Both Space ID and Personal Access Token are required.');
    rl.close();
    process.exit(1);
  }

  const client = createClient({
    accessToken: managementToken
  });

  try {
    console.log('\n⏳ Connecting to Contentful...');
    const space = await client.space.get({ spaceId });
    console.log(`✅ Connected to Space: ${space.name}`);

    console.log('⏳ Accessing master environment...');
    const environment = await client.environment.get({ spaceId, environmentId: 'master' });
    console.log('✅ Connected to master environment.');

    // Get default locale
    console.log('⏳ Fetching default locale...');
    const locales = await client.locale.getMany({ spaceId, environmentId: 'master' });
    const defaultLocale = locales.items.find(l => l.default)?.code || 'en-US';
    console.log(`✅ Default locale identified: ${defaultLocale}`);

    // Create Content Type
    console.log('⏳ Fetching or creating Content Type: portfolioProject...');
    let contentType;
    try {
      contentType = await client.contentType.get({ spaceId, environmentId: 'master', contentTypeId: 'portfolioProject' });
      console.log('ℹ️ Content Type "portfolioProject" already exists. Updating fields...');
    } catch (e) {
      // Doesn't exist, create it
    }

    const contentTypeConfig = {
      name: 'Portfolio Project',
      displayField: 'title',
      fields: [
        { id: 'title', name: 'Title', type: 'Symbol', required: true },
        { id: 'description', name: 'Description', type: 'Text' },
        { id: 'tags', name: 'Tags', type: 'Array', items: { type: 'Symbol' } },
        { id: 'url', name: 'URL', type: 'Symbol' },
        { id: 'imageUrl', name: 'Image URL', type: 'Symbol' },
        { id: 'status', name: 'Status', type: 'Symbol' }
      ]
    };

    if (contentType) {
      contentType.fields = contentTypeConfig.fields;
      contentType.name = contentTypeConfig.name;
      contentType.displayField = contentTypeConfig.displayField;
      contentType = await client.contentType.update({ spaceId, environmentId: 'master', contentTypeId: 'portfolioProject' }, contentType);
    } else {
      contentType = await client.contentType.createWithId({ spaceId, environmentId: 'master', contentTypeId: 'portfolioProject' }, contentTypeConfig);
    }

    console.log('⏳ Publishing Content Type...');
    contentType = await client.contentType.publish({ spaceId, environmentId: 'master', contentTypeId: 'portfolioProject' }, contentType);
    console.log('✅ Content Type published successfully!');

    // Create Mock Projects
    console.log('\n⏳ Seeding mock projects...');
    const mockProjects = [
      {
        id: 'mock-project-1',
        title: 'Re:SKY Portfolio',
        description: 'Minimalist developer portfolio built with Next.js App Router, Tailwind CSS, and Contentful CMS.',
        tags: ['Next.js', 'Tailwind CSS', 'Contentful', 'TypeScript'],
        url: 'https://github.com/PlutoniumQI',
        imageUrl: '',
        status: 'live'
      },
      {
        id: 'mock-project-2',
        title: 'Design System Builder',
        description: 'Interactive Figma-to-code design system engine for generating pixel-perfect React components.',
        tags: ['React', 'Figma API', 'Tailwind CSS'],
        url: '',
        imageUrl: '',
        status: 'coming_soon'
      },
      {
        id: 'mock-project-3',
        title: 'E-Commerce Minimal',
        description: 'Headless e-commerce template featuring server-side rendering, dynamic routes, and Stripe payment integration.',
        tags: ['Next.js', 'Stripe', 'Tailwind CSS'],
        url: 'https://example.com',
        imageUrl: '',
        status: 'live'
      }
    ];

    for (const proj of mockProjects) {
      console.log(`⏳ Seeding entry: ${proj.title}...`);
      let entry;
      try {
        entry = await client.entry.get({ spaceId, environmentId: 'master', entryId: proj.id });
        // Update existing
        entry.fields = {
          title: { [defaultLocale]: proj.title },
          description: { [defaultLocale]: proj.description },
          tags: { [defaultLocale]: proj.tags },
          url: { [defaultLocale]: proj.url },
          imageUrl: { [defaultLocale]: proj.imageUrl },
          status: { [defaultLocale]: proj.status }
        };
        entry = await client.entry.update({ spaceId, environmentId: 'master', entryId: proj.id }, entry);
      } catch (err) {
        // Create new
        entry = await client.entry.createWithId({ spaceId, environmentId: 'master', contentTypeId: 'portfolioProject', entryId: proj.id }, {
          fields: {
            title: { [defaultLocale]: proj.title },
            description: { [defaultLocale]: proj.description },
            tags: { [defaultLocale]: proj.tags },
            url: { [defaultLocale]: proj.url },
            imageUrl: { [defaultLocale]: proj.imageUrl },
            status: { [defaultLocale]: proj.status }
          }
        });
      }
      await client.entry.publish({ spaceId, environmentId: 'master', entryId: proj.id }, entry);
      console.log(`✅ Seeded & Published: ${proj.title}`);
    }

    // Generate API Keys for Delivery
    console.log('\n⏳ Generating Content Delivery API Key...');
    let apiKeys = await client.apiKey.getMany({ spaceId });
    let apiKey = apiKeys.items.find(k => k.name === 'NextJS Portfolio Key');
    if (!apiKey) {
      apiKey = await client.apiKey.create({ spaceId }, {
        name: 'NextJS Portfolio Key',
        description: 'Automatically generated token for Next.js App Router portfolio site.'
      });
    }
    const deliveryAccessToken = apiKey.accessToken;
    console.log('✅ Content Delivery API Key generated.');

    // Write to .env.local
    console.log('\n⏳ Updating .env.local...');
    const envContent = `# ─── Contentful ──────────────────────────────────────────────────────────────
CONTENTFUL_SPACE_ID=${spaceId}
CONTENTFUL_ACCESS_TOKEN=${deliveryAccessToken}
`;
    fs.writeFileSync(envPath, envContent, 'utf8');
    console.log('✅ .env.local updated successfully with live credentials!');

    console.log('\n🎉 Contentful setup complete!');
    console.log('You can now start the dev server: npm run dev');

  } catch (error) {
    console.error('\n❌ An error occurred during setup:', error.message || error);
  } finally {
    rl.close();
  }
}

main();
