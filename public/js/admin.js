/* Onyx admin helpers */
(() => {
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

    // Mobile sidebar
    const side = document.querySelector('[data-side]');
    document.querySelector('[data-side-toggle]')?.addEventListener('click', () => side.classList.toggle('open'));

    // Confirm destructive actions
    $$('form[data-confirm]').forEach((f) => f.addEventListener('submit', (e) => {
        if (!confirm(f.dataset.confirm)) e.preventDefault();
    }));

    // Warn before leaving with unsaved changes
    $$('form[data-dirty-guard]').forEach((form) => {
        let dirty = false;
        form.addEventListener('input', () => { dirty = true; });
        form.addEventListener('change', () => { dirty = true; });
        form.addEventListener('submit', () => { dirty = false; });
        window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
    });

    // Character counters on SEO and limited fields
    $$('[data-count]').forEach((el) => {
        const ideal = Number(el.dataset.count);
        const c = document.createElement('span');
        c.className = 'counter';
        el.insertAdjacentElement('afterend', c);
        const upd = () => { const n = el.value.length; c.textContent = `${n} / ${ideal}`; c.classList.toggle('over', n > ideal); };
        el.addEventListener('input', upd); upd();
    });

    // Slug: suggest from the title until someone edits it by hand
    const slug = document.querySelector('[data-slug]');
    const title = document.querySelector('#fld-title, #fld-name');
    if (slug && title) {
        let touched = slug.value !== '';
        slug.addEventListener('input', () => { touched = true; });
        title.addEventListener('input', () => {
            if (touched) return;
            slug.placeholder = title.value.toLowerCase().normalize('NFKD').replace(/[^\w\s-]/g, '').trim().replace(/[\s_]+/g, '-').replace(/-+/g, '-');
        });
    }

    // Live Google preview
    const serp = document.querySelector('[data-serp]');
    if (serp) {
        const t = document.querySelector('#fld-meta_title');
        const d = document.querySelector('#fld-meta_description');
        const ft = document.querySelector('#fld-title');
        const fd = document.querySelector('#fld-summary, #fld-excerpt');
        const st = serp.querySelector('[data-serp-title]');
        const sd = serp.querySelector('[data-serp-desc]');
        const upd = () => {
            st.textContent = ((t && t.value) || (ft && ft.value) || 'Page title').slice(0, 65);
            const desc = (d && d.value) || (fd && fd.value) || '';
            sd.textContent = desc.length > 160 ? desc.slice(0, 157) + '…' : desc;
        };
        [t, d, ft, fd].forEach((el) => el && el.addEventListener('input', upd));
        upd();
    }

    // Image preview before upload
    $$('[data-img-input]').forEach((input) => input.addEventListener('change', () => {
        const box = input.closest('.img-field').querySelector('[data-img-preview]');
        const file = input.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) { alert('That image is larger than 5 MB. Please choose a smaller one.'); input.value = ''; return; }
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        box.innerHTML = ''; box.appendChild(img);
    }));

    // Markdown toolbar and preview
    const tiny = (md) => {
        const esc = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const inline = (s) => esc(s).replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>').replace(/\*(.+?)\*/g, '<em>$1</em>').replace(/\[(.+?)\]\((https?:\/\/[^\s)]+|\/[^\s)]*)\)/g, '<a href="$2">$1</a>');
        return md.split(/\n{2,}/).map((block) => {
            if (/^###\s/.test(block)) return `<h3>${inline(block.slice(4))}</h3>`;
            if (/^##\s/.test(block)) return `<h2>${inline(block.slice(3))}</h2>`;
            if (/^>\s/.test(block)) return `<blockquote>${inline(block.replace(/^>\s?/gm, ''))}</blockquote>`;
            if (/^[-*]\s/.test(block)) return '<ul>' + block.split('\n').map((l) => `<li>${inline(l.replace(/^[-*]\s/, ''))}</li>`).join('') + '</ul>';
            return `<p>${inline(block).replace(/\n/g, '<br>')}</p>`;
        }).join('');
    };
    $$('.md').forEach((md) => {
        const ta = md.querySelector('textarea');
        const pv = md.querySelector('.md-preview');
        const wrap = (before, after = before, placeholder = 'text') => {
            const { selectionStart: s, selectionEnd: e, value } = ta;
            const sel = value.slice(s, e) || placeholder;
            ta.setRangeText(before + sel + after, s, e, 'select');
            ta.focus(); ta.dispatchEvent(new Event('input', { bubbles: true }));
        };
        const line = (prefix) => {
            const s = ta.selectionStart;
            const start = ta.value.lastIndexOf('\n', s - 1) + 1;
            ta.setRangeText(prefix, start, start, 'end');
            ta.focus(); ta.dispatchEvent(new Event('input', { bubbles: true }));
        };
        md.querySelector('.md-bar').addEventListener('click', (e) => {
            const b = e.target.closest('button'); if (!b) return;
            if (b.hasAttribute('data-md-preview')) {
                const show = pv.hidden;
                pv.hidden = !show; ta.hidden = show;
                if (show) pv.innerHTML = tiny(ta.value);
                b.textContent = show ? 'Edit' : 'Preview';
                return;
            }
            ({ bold: () => wrap('**'), italic: () => wrap('*'), heading: () => line('## '), list: () => line('- '), quote: () => line('> '),
               link: () => { const url = prompt('Link address (https://…)'); if (url) wrap('[', `](${url})`, 'link text'); } })[b.dataset.md]?.();
        });
    });
})();
