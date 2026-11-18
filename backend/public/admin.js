(() => {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

  /* Mobile sidebar ------------------------------------------------------- */
  const sidebar = document.getElementById('adminSidebar');
  const toggle = document.getElementById('sidebarToggle');

  toggle?.addEventListener('click', () => sidebar?.classList.toggle('open'));

  document.addEventListener('click', (event) => {
    if (!sidebar?.classList.contains('open')) return;
    if (sidebar.contains(event.target) || toggle?.contains(event.target)) return;
    sidebar.classList.remove('open');
  });

  /* Confirm-before-submit forms ----------------------------------------- */
  document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!window.confirm(form.dataset.confirm ?? 'Are you sure?')) {
        event.preventDefault();
      }
    });
  });

  /* Auto-hide flash messages -------------------------------------------- */
  document.querySelectorAll('.flash.success').forEach((flash) => {
    window.setTimeout(() => {
      flash.classList.add('is-hiding');
      window.setTimeout(() => flash.remove(), 350);
    }, 5000);
  });

  /* Rich text toolbar --------------------------------------------------- */
  document.querySelectorAll('[data-editor]').forEach((editor) => {
    const textarea = editor.querySelector('textarea');

    if (!textarea) return;

    const wrap = (before, after = before) => {
      const start = textarea.selectionStart;
      const end = textarea.selectionEnd;
      const selected = textarea.value.slice(start, end) || 'text';
      textarea.setRangeText(`${before}${selected}${after}`, start, end, 'end');
      textarea.focus();
    };

    const block = (tag) => {
      const start = textarea.selectionStart;
      const end = textarea.selectionEnd;
      const selected = textarea.value.slice(start, end) || 'Text';
      textarea.setRangeText(`<${tag}>${selected}</${tag}>`, start, end, 'end');
      textarea.focus();
    };

    editor.querySelectorAll('[data-format]').forEach((button) => {
      button.addEventListener('click', () => {
        switch (button.dataset.format) {
          case 'bold':
            wrap('<strong>', '</strong>');
            break;
          case 'italic':
            wrap('<em>', '</em>');
            break;
          case 'h3':
            block('h3');
            break;
          case 'p':
            block('p');
            break;
          case 'ul':
            wrap('<ul>\n  <li>', '</li>\n</ul>');
            break;
          case 'link': {
            const url = window.prompt('Link URL', 'https://');
            if (url) {
              const start = textarea.selectionStart;
              const end = textarea.selectionEnd;
              const selected = textarea.value.slice(start, end) || 'link text';
              textarea.setRangeText(`<a href="${url}">${selected}</a>`, start, end, 'end');
              textarea.focus();
            }
            break;
          }
          default:
            break;
        }
      });
    });
  });

  /* Gallery preview ----------------------------------------------------- */
  document.querySelectorAll('[data-gallery]').forEach((textarea) => {
    const preview = textarea.parentElement?.querySelector('[data-gallery-preview]');

    if (!preview) return;

    const render = () => {
      preview.replaceChildren();

      textarea.value
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter((line) => /^https?:\/\//i.test(line))
        .slice(0, 24)
        .forEach((url) => {
          const image = document.createElement('img');
          image.src = url;
          image.alt = '';
          image.loading = 'lazy';
          image.referrerPolicy = 'no-referrer';
          preview.appendChild(image);
        });
    };

    textarea.addEventListener('input', render);
    render();
  });

  /* Drag-and-drop ordering --------------------------------------------- */
  document.querySelectorAll('[data-sortable]').forEach((tbody) => {
    const table = tbody.closest('table');
    const url = table?.dataset.reorderUrl;

    if (!url || typeof window.Sortable === 'undefined') return;

    window.Sortable.create(tbody, {
      handle: '.drag-handle',
      animation: 150,
      onEnd: async () => {
        const ids = Array.from(tbody.querySelectorAll('[data-id]')).map((row) => row.dataset.id);

        try {
          const response = await fetch(url, {
            method: 'POST',
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ ids }),
          });

          if (!response.ok) throw new Error('Reorder failed');
        } catch {
          window.location.reload();
        }
      },
    });
  });
})();
