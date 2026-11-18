<?php

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;

return [

    /*
    |--------------------------------------------------------------------------
    | Admin resource definitions
    |--------------------------------------------------------------------------
    |
    | Each resource is rendered by the same controller and Blade form. Keep
    | this file to plain arrays so `php artisan config:cache` can export it;
    | closures and objects are not cacheable.
    |
    */

    'resources' => [

        'projects' => [
            'label' => 'Projects',
            'singular' => 'Project',
            'model' => Project::class,
            'title_column' => 'title',
            'reorder' => true,
            'search' => ['title', 'title_en', 'slug'],
            'columns' => [
                ['name' => 'title', 'label' => 'Title'],
                ['name' => 'category', 'label' => 'Category', 'type' => 'map', 'map' => Project::categories()],
                ['name' => 'status', 'label' => 'Status', 'type' => 'map', 'map' => Project::statuses()],
                ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
                ['name' => 'sort_order', 'label' => '#'],
            ],
            'groups' => [
                [
                    'title' => 'Project details',
                    'description' => 'Bilingual content shown on the project card and detail page.',
                    'fields' => [
                        ['name' => 'title', 'label' => 'Title (ID)', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'title_en', 'label' => 'Title (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255'], 'help' => 'Falls back to Indonesian when empty.'],
                        ['name' => 'description', 'label' => 'Short description (ID)', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:500']],
                        ['name' => 'description_en', 'label' => 'Short description (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:500']],
                        ['name' => 'excerpt_id', 'label' => 'Excerpt (ID)', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:500']],
                        ['name' => 'excerpt_en', 'label' => 'Excerpt (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:500']],
                        ['name' => 'content_id', 'label' => 'Case study / process story (ID)', 'type' => 'richtext', 'rules' => ['nullable', 'string'], 'wide' => true],
                        ['name' => 'content_en', 'label' => 'Case study / process story (EN)', 'type' => 'richtext', 'rules' => ['nullable', 'string'], 'wide' => true],
                    ],
                ],
                [
                    'title' => 'Links and classification',
                    'fields' => [
                        ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'rules' => ['required', 'string', 'max:255', 'unique:projects,slug,{id}'], 'help' => 'Used in /projects/{slug}. Lowercase letters, numbers, and hyphens.'],
                        ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => Project::categories(), 'rules' => ['nullable', 'string']],
                        ['name' => 'url', 'label' => 'Live URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                        ['name' => 'repo_url', 'label' => 'Repository URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                        ['name' => 'tags', 'label' => 'Tech stack / tags', 'type' => 'tags', 'rules' => ['nullable', 'string'], 'wide' => true, 'help' => 'Separate tags with commas.'],
                    ],
                ],
                [
                    'title' => 'Media',
                    'description' => 'Paste public image URLs. Uploads are not stored, so GitHub raw URLs are recommended.',
                    'fields' => [
                        ['name' => 'image_url', 'label' => 'Cover image URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                        ['name' => 'gallery', 'label' => 'Gallery image URLs', 'type' => 'gallery', 'rules' => ['nullable', 'string'], 'wide' => true, 'help' => 'One public URL per line.'],
                    ],
                ],
                [
                    'title' => 'Publishing',
                    'fields' => [
                        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => Project::statuses(), 'rules' => ['required', 'string']],
                        ['name' => 'is_featured', 'label' => 'Feature on the home page', 'type' => 'checkbox', 'rules' => ['boolean']],
                        ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0'], 'help' => 'Lower numbers appear first. Drag rows on the list to reorder.'],
                    ],
                ],
            ],
        ],

        'experiences' => [
            'label' => 'Experience',
            'singular' => 'Experience',
            'model' => Experience::class,
            'title_column' => 'title',
            'reorder' => true,
            'search' => ['title', 'title_en', 'company'],
            'columns' => [
                ['name' => 'title', 'label' => 'Title'],
                ['name' => 'company', 'label' => 'Company'],
                ['name' => 'type', 'label' => 'Type', 'type' => 'map', 'map' => Experience::types()],
                ['name' => 'date_range', 'label' => 'Period'],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
            ],
            'groups' => [
                [
                    'title' => 'Experience details',
                    'fields' => [
                        ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => Experience::types(), 'rules' => ['required', 'string']],
                        ['name' => 'company', 'label' => 'Company / organisation', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'title', 'label' => 'Title (ID)', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'title_en', 'label' => 'Title (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'date_range', 'label' => 'Period', 'type' => 'text', 'rules' => ['required', 'string', 'max:120'], 'help' => 'For example: 2024 — Present'],
                        ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'url', 'label' => 'Link', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                        ['name' => 'description', 'label' => 'Description (ID)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                        ['name' => 'description_en', 'label' => 'Description (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                        ['name' => 'tags', 'label' => 'Tags', 'type' => 'tags', 'rules' => ['nullable', 'string'], 'wide' => true, 'help' => 'Separate tags with commas.'],
                    ],
                ],
                [
                    'title' => 'Visibility',
                    'fields' => [
                        ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
                        ['name' => 'is_active', 'label' => 'Visible on the site', 'type' => 'checkbox', 'rules' => ['boolean']],
                    ],
                ],
            ],
        ],

        'posts' => [
            'label' => 'Blog posts',
            'singular' => 'Post',
            'model' => Post::class,
            'title_column' => 'title_id',
            'reorder' => false,
            'search' => ['title_id', 'title_en', 'slug'],
            'columns' => [
                ['name' => 'title_id', 'label' => 'Title'],
                ['name' => 'category', 'label' => 'Category'],
                ['name' => 'status', 'label' => 'Status', 'type' => 'map', 'map' => Post::statuses()],
                ['name' => 'published_at', 'label' => 'Published', 'type' => 'datetime'],
                ['name' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
            ],
            'groups' => [
                [
                    'title' => 'Post',
                    'fields' => [
                        ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'rules' => ['required', 'string', 'max:255', 'unique:posts,slug,{id}'], 'help' => 'Used in /blog/{slug}.'],
                        ['name' => 'category', 'label' => 'Category', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => Post::statuses(), 'rules' => ['required', 'string']],
                        ['name' => 'published_at', 'label' => 'Publish date', 'type' => 'datetime', 'rules' => ['nullable', 'date'], 'help' => 'Required before a published post appears on the site.'],
                        ['name' => 'is_featured', 'label' => 'Feature on the home page', 'type' => 'checkbox', 'rules' => ['boolean']],
                        ['name' => 'tags', 'label' => 'Tags', 'type' => 'tags', 'rules' => ['nullable', 'string'], 'wide' => true],
                    ],
                ],
                [
                    'title' => 'Indonesia',
                    'fields' => [
                        ['name' => 'title_id', 'label' => 'Title (ID)', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'excerpt_id', 'label' => 'Excerpt (ID)', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:500']],
                        ['name' => 'body_id', 'label' => 'Body (ID)', 'type' => 'richtext', 'rules' => ['required', 'string'], 'wide' => true],
                    ],
                ],
                [
                    'title' => 'English',
                    'fields' => [
                        ['name' => 'title_en', 'label' => 'Title (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'excerpt_en', 'label' => 'Excerpt (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:500']],
                        ['name' => 'body_en', 'label' => 'Body (EN)', 'type' => 'richtext', 'rules' => ['nullable', 'string'], 'wide' => true],
                    ],
                ],
                [
                    'title' => 'Cover image',
                    'fields' => [
                        ['name' => 'cover_url', 'label' => 'Cover image URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048'], 'wide' => true],
                    ],
                ],
            ],
        ],

        'skills' => [
            'label' => 'Skills',
            'singular' => 'Skill',
            'model' => Skill::class,
            'title_column' => 'name',
            'reorder' => true,
            'search' => ['name'],
            'columns' => [
                ['name' => 'name', 'label' => 'Name'],
                ['name' => 'category', 'label' => 'Category', 'type' => 'map', 'map' => Skill::categories()],
                ['name' => 'level', 'label' => 'Level', 'type' => 'map', 'map' => Skill::levels()],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
            ],
            'groups' => [
                [
                    'title' => 'Skill',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => Skill::categories(), 'rules' => ['required', 'string']],
                        ['name' => 'level', 'label' => 'Level', 'type' => 'select', 'options' => Skill::levels(), 'rules' => ['required', 'string']],
                        ['name' => 'icon', 'label' => 'Icon name', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:100']],
                        ['name' => 'description_id', 'label' => 'Description (ID)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                        ['name' => 'description_en', 'label' => 'Description (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                    ],
                ],
                [
                    'title' => 'Visibility',
                    'fields' => [
                        ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
                        ['name' => 'is_active', 'label' => 'Visible on the site', 'type' => 'checkbox', 'rules' => ['boolean']],
                    ],
                ],
            ],
        ],

        'education' => [
            'label' => 'Education',
            'singular' => 'Education',
            'model' => Education::class,
            'title_column' => 'institution',
            'reorder' => true,
            'search' => ['institution', 'degree_en', 'degree_id'],
            'columns' => [
                ['name' => 'institution', 'label' => 'Institution'],
                ['name' => 'degree_en', 'label' => 'Degree'],
                ['name' => 'start_date', 'label' => 'Start'],
                ['name' => 'end_date', 'label' => 'End'],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
            ],
            'groups' => [
                [
                    'title' => 'School / campus',
                    'fields' => [
                        ['name' => 'institution', 'label' => 'Institution', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'start_date', 'label' => 'Start year', 'type' => 'text', 'rules' => ['required', 'string', 'max:20']],
                        ['name' => 'end_date', 'label' => 'End year', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:20']],
                        ['name' => 'degree_id', 'label' => 'Degree (ID)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'degree_en', 'label' => 'Degree (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'field_id', 'label' => 'Field (ID)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'field_en', 'label' => 'Field (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'description_id', 'label' => 'Description (ID)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                        ['name' => 'description_en', 'label' => 'Description (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                    ],
                ],
                [
                    'title' => 'Visibility',
                    'fields' => [
                        ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
                        ['name' => 'is_active', 'label' => 'Visible on the site', 'type' => 'checkbox', 'rules' => ['boolean']],
                    ],
                ],
            ],
        ],

        'certificates' => [
            'label' => 'Certificates',
            'singular' => 'Certificate',
            'model' => Certificate::class,
            'title_column' => 'title_id',
            'reorder' => true,
            'search' => ['title_id', 'title_en', 'issuer'],
            'columns' => [
                ['name' => 'title_id', 'label' => 'Title'],
                ['name' => 'issuer', 'label' => 'Issuer'],
                ['name' => 'issued_at', 'label' => 'Issued', 'type' => 'date'],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
            ],
            'groups' => [
                [
                    'title' => 'Certificate',
                    'fields' => [
                        ['name' => 'title_id', 'label' => 'Title (ID)', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'title_en', 'label' => 'Title (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'issuer', 'label' => 'Issuer', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'issued_at', 'label' => 'Issued at', 'type' => 'date', 'rules' => ['nullable', 'date']],
                        ['name' => 'expires_at', 'label' => 'Expires at', 'type' => 'date', 'rules' => ['nullable', 'date']],
                        ['name' => 'credential_id', 'label' => 'Credential ID', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'credential_url', 'label' => 'Credential URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                        ['name' => 'image_url', 'label' => 'Preview image URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                        ['name' => 'description_id', 'label' => 'Description (ID)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                        ['name' => 'description_en', 'label' => 'Description (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                    ],
                ],
                [
                    'title' => 'Visibility',
                    'fields' => [
                        ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
                        ['name' => 'is_active', 'label' => 'Visible on the site', 'type' => 'checkbox', 'rules' => ['boolean']],
                    ],
                ],
            ],
        ],

        'testimonials' => [
            'label' => 'Testimonials',
            'singular' => 'Testimonial',
            'model' => Testimonial::class,
            'title_column' => 'name',
            'reorder' => true,
            'search' => ['name', 'company', 'quote_id'],
            'columns' => [
                ['name' => 'name', 'label' => 'Name'],
                ['name' => 'company', 'label' => 'Company'],
                ['name' => 'category', 'label' => 'Category', 'type' => 'map', 'map' => Testimonial::categories()],
                ['name' => 'rating', 'label' => 'Rating'],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
            ],
            'groups' => [
                [
                    'title' => 'Testimonial',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                        ['name' => 'company', 'label' => 'Company / school', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'options' => Testimonial::categories(), 'rules' => ['required', 'string']],
                        ['name' => 'rating', 'label' => 'Rating (1-5)', 'type' => 'number', 'rules' => ['required', 'integer', 'between:1,5']],
                        ['name' => 'role_id', 'label' => 'Role (ID)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'role_en', 'label' => 'Role (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                        ['name' => 'quote_id', 'label' => 'Quote (ID)', 'type' => 'textarea', 'rules' => ['required', 'string'], 'wide' => true],
                        ['name' => 'quote_en', 'label' => 'Quote (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string'], 'wide' => true],
                        ['name' => 'avatar_url', 'label' => 'Avatar image URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                    ],
                ],
                [
                    'title' => 'Visibility',
                    'fields' => [
                        ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
                        ['name' => 'is_active', 'label' => 'Visible on the site', 'type' => 'checkbox', 'rules' => ['boolean']],
                    ],
                ],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Profile & CV (singleton)
    |--------------------------------------------------------------------------
    */

    'profile' => [
        [
            'title' => 'Identity',
            'fields' => [
                ['name' => 'full_name', 'label' => 'Full name', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                ['name' => 'email', 'label' => 'Public email', 'type' => 'email', 'rules' => ['nullable', 'email', 'max:255']],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:50']],
                ['name' => 'years_learning', 'label' => 'Years learning', 'type' => 'number', 'rules' => ['nullable', 'integer', 'between:0,80']],
                ['name' => 'tools_mastered', 'label' => 'Tools mastered', 'type' => 'number', 'rules' => ['nullable', 'integer', 'between:0,100']],
                ['name' => 'is_available', 'label' => 'Available for work / collaboration', 'type' => 'checkbox', 'rules' => ['boolean']],
            ],
        ],
        [
            'title' => 'Indonesia',
            'fields' => [
                ['name' => 'headline_id', 'label' => 'Headline (ID)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                ['name' => 'availability_id', 'label' => 'Availability label (ID)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                ['name' => 'intro_id', 'label' => 'Hero intro (ID)', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:500']],
                ['name' => 'about_id', 'label' => 'About (ID)', 'type' => 'richtext', 'rules' => ['nullable', 'string'], 'wide' => true],
            ],
        ],
        [
            'title' => 'English',
            'fields' => [
                ['name' => 'headline_en', 'label' => 'Headline (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                ['name' => 'availability_en', 'label' => 'Availability label (EN)', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
                ['name' => 'intro_en', 'label' => 'Hero intro (EN)', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:500']],
                ['name' => 'about_en', 'label' => 'About (EN)', 'type' => 'richtext', 'rules' => ['nullable', 'string'], 'wide' => true],
            ],
        ],
        [
            'title' => 'Links',
            'fields' => [
                ['name' => 'linkedin_url', 'label' => 'LinkedIn', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                ['name' => 'github_url', 'label' => 'GitHub', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                ['name' => 'instagram_url', 'label' => 'Instagram', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                ['name' => 'website_url', 'label' => 'Website', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
            ],
        ],
        [
            'title' => 'Media',
            'description' => 'Paste public URLs. GitHub raw links work for both the avatar and the CV PDF.',
            'fields' => [
                ['name' => 'avatar_url', 'label' => 'Avatar image URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
                ['name' => 'cv_url', 'label' => 'CV PDF URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:2048']],
            ],
        ],
    ],

];
