/* Onyx Innovations — site interactions. No dependencies. */
(() => {
    'use strict';

    const doc = document.documentElement;
    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const finePointer = window.matchMedia('(pointer: fine)').matches;

    /* ---------- Split headlines into words for the load sequence ---------- */
    const splitWords = (el, cls = 'w') => {
        if (!el || el.dataset.splitDone) return;
        const words = el.textContent.trim().split(/\s+/);
        el.setAttribute('aria-label', el.textContent.trim());
        el.innerHTML = words.map((w, i) => `<span class="${cls}" aria-hidden="true"><span style="--i:${i}">${w.replace(/</g, '&lt;')}</span></span>`).join(' ');
        el.dataset.splitDone = '1';
    };
    $$('[data-pour], [data-split]').forEach((el) => splitWords(el));

    // Statement: each word lights up as it scrolls through the viewport
    const statement = $('[data-words]');
    let statementWords = [];
    if (statement) {
        const words = statement.textContent.trim().split(/\s+/);
        statement.setAttribute('aria-label', statement.textContent.trim());
        statement.innerHTML = words.map((w) => `<span class="wd" aria-hidden="true">${w.replace(/</g, '&lt;')}</span>`).join(' ');
        statementWords = $$('.wd', statement);
    }

    const markLoaded = () => requestAnimationFrame(() => doc.classList.add('is-loaded'));
    if (document.fonts && document.fonts.ready) {
        Promise.race([document.fonts.ready, new Promise((r) => setTimeout(r, 900))]).then(markLoaded);
    } else {
        window.addEventListener('load', markLoaded);
    }

    /* ---------- Header, progress bar and scroll-linked effects ---------- */
    const header = $('[data-header]');
    const progress = $('.progress span');
    const steps = $('[data-steps]');
    let lastY = window.scrollY;
    let ticking = false;

    const onScroll = () => {
        const y = window.scrollY;
        const max = document.body.scrollHeight - window.innerHeight;
        if (header) {
            header.classList.toggle('is-scrolled', y > 8);
            const menuOpen = doc.classList.contains('menu-open');
            header.classList.toggle('is-hidden', !menuOpen && y > 600 && y > lastY + 6);
            if (y < lastY - 4) header.classList.remove('is-hidden');
        }
        if (progress && max > 0) progress.style.setProperty('--p', Math.min(1, y / max).toFixed(4));

        if (statementWords.length) {
            const r = statement.getBoundingClientRect();
            const vh = window.innerHeight;
            const t = Math.min(1, Math.max(0, (vh * 0.85 - r.top) / (r.height + vh * 0.35)));
            const n = Math.round(t * statementWords.length);
            statementWords.forEach((w, i) => w.classList.toggle('on', reduced || i < n));
        }

        if (steps) {
            const r = steps.getBoundingClientRect();
            const vh = window.innerHeight;
            const vertical = window.innerWidth <= 1080;
            const t = Math.min(1, Math.max(0, (vh * 0.7 - r.top) / (vertical ? r.height : r.height + vh * 0.25)));
            steps.style.setProperty('--prog', t.toFixed(3));
            const items = $$('.step', steps);
            items.forEach((s, i) => s.classList.toggle('lit', t >= (i + 0.5) / items.length - 0.05));
        }

        lastY = y;
        ticking = false;
    };
    window.addEventListener('scroll', () => {
        if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
    }, { passive: true });
    onScroll();

    /* ---------- Mobile menu ---------- */
    const toggle = $('[data-menu-toggle]');
    const menu = $('[data-menu]');
    if (toggle && menu) {
        const setOpen = (open) => {
            toggle.setAttribute('aria-expanded', String(open));
            $('.sr-only', toggle).textContent = open ? 'Close menu' : 'Open menu';
            if (open) { menu.hidden = false; requestAnimationFrame(() => doc.classList.add('menu-open')); }
            else { doc.classList.remove('menu-open'); setTimeout(() => { if (!doc.classList.contains('menu-open')) menu.hidden = true; }, 300); }
        };
        toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && doc.classList.contains('menu-open')) { setOpen(false); toggle.focus(); } });
        $$('a', menu).forEach((a) => a.addEventListener('click', () => setOpen(false)));
    }

    /* ---------- Reveal on scroll ---------- */
    const revealIO = 'IntersectionObserver' in window ? new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (e.isIntersecting) { e.target.classList.add('in'); revealIO.unobserve(e.target); }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 }) : null;

    // Stagger siblings that reveal together
    $$('[data-reveal]').forEach((el) => {
        if (!el.style.getPropertyValue('--i') && el.parentElement) {
            const sibs = Array.from(el.parentElement.children).filter((c) => c.hasAttribute('data-reveal'));
            el.style.setProperty('--i', String(Math.min(sibs.indexOf(el), 5)));
        }
        if (revealIO && !reduced) revealIO.observe(el); else el.classList.add('in');
    });

    /* ---------- Ledger illustration ---------- */
    $$('[data-ledger]').forEach((ledger) => {
        const big = $('[data-ledger-count]', ledger);
        const run = () => {
            ledger.classList.add('in');
            if (!big || reduced) return;
            const target = Number(big.dataset.ledgerCount);
            const start = performance.now();
            const dur = 1800;
            const tick = (now) => {
                const t = Math.min(1, (now - start) / dur);
                const eased = 1 - Math.pow(1 - t, 4);
                big.textContent = '₦' + Math.round(target * eased).toLocaleString('en-NG');
                if (t < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        };
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((en) => { if (en[0].isIntersecting) { run(); io.disconnect(); } }, { threshold: 0.35 });
            io.observe(ledger);
        } else run();
    });

    /* ---------- Count up figures ---------- */
    $$('[data-count]').forEach((el) => {
        const raw = el.dataset.count;
        const m = raw.match(/^([^\d]*)([\d,.]+)(.*)$/);
        if (!m || reduced || !('IntersectionObserver' in window)) return;
        const [, pre, num, post] = m;
        const target = parseFloat(num.replace(/,/g, ''));
        const decimals = (num.split('.')[1] || '').length;
        const io = new IntersectionObserver((en) => {
            if (!en[0].isIntersecting) return;
            io.disconnect();
            const start = performance.now();
            const tick = (now) => {
                const t = Math.min(1, (now - start) / 1600);
                const v = target * (1 - Math.pow(1 - t, 3));
                el.textContent = pre + v.toLocaleString('en-NG', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + post;
                if (t < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        }, { threshold: 0.6 });
        io.observe(el);
    });

    /* ---------- Magnetic buttons and tilting cards ---------- */
    if (finePointer && !reduced) {
        $$('[data-magnetic]').forEach((btn) => {
            btn.addEventListener('pointermove', (e) => {
                const r = btn.getBoundingClientRect();
                const x = (e.clientX - r.left - r.width / 2) * 0.22;
                const y = (e.clientY - r.top - r.height / 2) * 0.32;
                btn.style.transform = `translate(${x}px, ${y}px)`;
            });
            btn.addEventListener('pointerleave', () => { btn.style.transform = ''; });
        });

        $$('[data-tilt] .work-card-media').forEach((card) => {
            card.addEventListener('pointermove', (e) => {
                const r = card.getBoundingClientRect();
                const px = (e.clientX - r.left) / r.width - 0.5;
                const py = (e.clientY - r.top) / r.height - 0.5;
                card.style.transform = `perspective(1100px) rotateY(${px * 5}deg) rotateX(${-py * 5}deg)`;
                const label = $('.work-card-view', card);
                if (label) { label.style.left = `${(px + 0.5) * 100}%`; label.style.top = `${(py + 0.5) * 100}%`; }
            });
            card.addEventListener('pointerleave', () => { card.style.transform = ''; });
        });
    }

    /* ---------- Testimonial slider ---------- */
    $$('[data-slider]').forEach((slider) => {
        const slides = $$('.quote-slide', slider);
        if (slides.length < 2) return;
        let i = 0;
        let timer;
        const show = (n) => {
            slides[i].hidden = true;
            i = (n + slides.length) % slides.length;
            slides[i].hidden = false;
            slides[i].classList.remove('entering'); void slides[i].offsetWidth; slides[i].classList.add('entering');
        };
        const auto = () => { clearInterval(timer); if (!reduced) timer = setInterval(() => show(i + 1), 8000); };
        $('[data-next]', slider)?.addEventListener('click', () => { show(i + 1); auto(); });
        $('[data-prev]', slider)?.addEventListener('click', () => { show(i - 1); auto(); });
        slider.addEventListener('pointerenter', () => clearInterval(timer));
        slider.addEventListener('pointerleave', auto);
        auto();
    });

    /* ---------- Newsletter form ---------- */
    $$('[data-subscribe]').forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = $('.subscribe-msg', form);
            const btn = $('button', form);
            btn.disabled = true;
            try {
                const res = await fetch(form.action, { method: 'POST', headers: { Accept: 'application/json' }, body: new FormData(form) });
                const data = await res.json().catch(() => ({}));
                if (res.ok) { msg.textContent = data.message || 'You are on the list. Thank you.'; form.reset(); }
                else if (res.status === 429) msg.textContent = 'Too many tries. Please wait a few minutes.';
                else msg.textContent = (data.errors && data.errors.email && data.errors.email[0]) || 'Enter a valid email address.';
            } catch {
                msg.textContent = 'Could not reach the server. Check your connection and try again.';
            }
            btn.disabled = false;
        });
    });

    /* ---------- Spam timer for enquiry forms ---------- */
    $$('input[name="_started"]').forEach((i) => { i.value = Math.floor(Date.now() / 1000); });

    /* ---------- Article table of contents ---------- */
    const tocLinks = $$('.toc a');
    if (tocLinks.length && 'IntersectionObserver' in window) {
        const map = new Map(tocLinks.map((a) => [a.getAttribute('href').slice(1), a]));
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    tocLinks.forEach((a) => a.classList.remove('on'));
                    map.get(e.target.id)?.classList.add('on');
                }
            });
        }, { rootMargin: '-20% 0px -70% 0px' });
        map.forEach((_, id) => { const h = document.getElementById(id); if (h) io.observe(h); });
    }

    /* ---------- Share buttons ---------- */
    $$('[data-copy]').forEach((b) => b.addEventListener('click', async () => {
        try { await navigator.clipboard.writeText(b.dataset.copy); b.querySelector('span').textContent = 'Link copied'; } catch { /* ignore */ }
    }));

    /* ==========================================================================
       The onyx stone: a low-poly gem, lit in gold, turning slowly and leaning
       toward the pointer. Drawn on a 2D canvas with simple 3D projection.
       ========================================================================== */
    const stoneCanvas = $('[data-stone]');
    if (stoneCanvas && stoneCanvas.getContext) {
        const ctx = stoneCanvas.getContext('2d');
        let W = 0; let H = 0; let dpr = 1;

        // Build an icosphere (one subdivision), then jitter it so it reads as a cut stone.
        const t = (1 + Math.sqrt(5)) / 2;
        let verts = [[-1, t, 0], [1, t, 0], [-1, -t, 0], [1, -t, 0], [0, -1, t], [0, 1, t], [0, -1, -t], [0, 1, -t], [t, 0, -1], [t, 0, 1], [-t, 0, -1], [-t, 0, 1]]
            .map((v) => { const l = Math.hypot(...v); return v.map((c) => c / l); });
        let faces = [[0, 11, 5], [0, 5, 1], [0, 1, 7], [0, 7, 10], [0, 10, 11], [1, 5, 9], [5, 11, 4], [11, 10, 2], [10, 7, 6], [7, 1, 8], [3, 9, 4], [3, 4, 2], [3, 2, 6], [3, 6, 8], [3, 8, 9], [4, 9, 5], [2, 4, 11], [6, 2, 10], [8, 6, 7], [9, 8, 1]];
        const cache = {};
        const mid = (a, b) => {
            const k = a < b ? a + '_' + b : b + '_' + a;
            if (cache[k] !== undefined) return cache[k];
            const v = verts[a].map((c, i) => (c + verts[b][i]) / 2);
            const l = Math.hypot(...v);
            verts.push(v.map((c) => c / l));
            return (cache[k] = verts.length - 1);
        };
        faces = faces.flatMap(([a, b, c]) => { const ab = mid(a, b), bc = mid(b, c), ca = mid(c, a); return [[a, ab, ca], [b, bc, ab], [c, ca, bc], [ab, bc, ca]]; });
        let seed = 7;
        const rnd = () => { seed = (seed * 16807) % 2147483647; return seed / 2147483647; };
        verts = verts.map((v) => { const k = 0.9 + rnd() * 0.2; return [v[0] * k * 1.08, v[1] * k * 0.92, v[2] * k]; });

        const light = (() => { const l = [-0.5, -0.65, 0.75]; const n = Math.hypot(...l); return l.map((c) => c / n); })();
        let rotY = 0.6; let rotX = -0.25;
        let targetLeanX = 0; let targetLeanY = 0; let leanX = 0; let leanY = 0;
        let running = true;
        let visible = true;

        const resize = () => {
            const r = stoneCanvas.getBoundingClientRect();
            dpr = Math.min(window.devicePixelRatio || 1, 2);
            W = r.width; H = r.height;
            stoneCanvas.width = Math.round(W * dpr); stoneCanvas.height = Math.round(H * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        };

        const mix = (a, b, k) => a.map((c, i) => Math.round(c + (b[i] - c) * k));
        const deep = [8, 24, 44]; const navy = [20, 52, 92]; const steel = [52, 104, 160]; const gold = [214, 162, 52]; const shine = [250, 228, 160];

        const draw = (time) => {
            if (!running) return;
            if (visible) {
                if (!reduced) rotY += 0.0018;
                leanX += (targetLeanX - leanX) * 0.04;
                leanY += (targetLeanY - leanY) * 0.04;
                const ay = rotY + leanX * 0.3;
                const ax = rotX + leanY * 0.4 + Math.sin(time / 4000) * 0.05;
                const cy = Math.cos(ay), sy = Math.sin(ay), cx = Math.cos(ax), sx = Math.sin(ax);
                const R = Math.min(W, H) * 0.3;
                const ox = W * 0.55; const oy = H * 0.44;

                const proj = verts.map(([x, y, z]) => {
                    let x1 = x * cy + z * sy; let z1 = -x * sy + z * cy;
                    let y1 = y * cx - z1 * sx; let z2 = y * sx + z1 * cx;
                    const p = 3.2 / (3.2 - z2);
                    return [ox + x1 * R * p, oy + y1 * R * p, z2, [x1, y1, z2]];
                });

                ctx.clearRect(0, 0, W, H);

                // Soft glow behind the stone
                const g = ctx.createRadialGradient(ox, oy, R * 0.2, ox, oy, R * 1.9);
                g.addColorStop(0, 'rgba(228,189,98,0.16)'); g.addColorStop(1, 'rgba(228,189,98,0)');
                ctx.fillStyle = g; ctx.fillRect(0, 0, W, H);

                const list = faces.map((f) => {
                    const [a, b, c] = f.map((i) => proj[i][3]);
                    const u = [b[0] - a[0], b[1] - a[1], b[2] - a[2]];
                    const v = [c[0] - a[0], c[1] - a[1], c[2] - a[2]];
                    let n = [u[1] * v[2] - u[2] * v[1], u[2] * v[0] - u[0] * v[2], u[0] * v[1] - u[1] * v[0]];
                    const nl = Math.hypot(...n) || 1; n = n.map((q) => q / nl);
                    return { f, z: (a[2] + b[2] + c[2]) / 3, n };
                }).filter((o) => o.n[2] > 0).sort((p, q) => p.z - q.z);

                list.forEach(({ f, n }) => {
                    const d = Math.max(0, n[0] * light[0] + n[1] * light[1] + n[2] * light[2]);
                    const rim = Math.pow(1 - n[2], 2);
                    // Navy stone; gold only where the light catches it.
                    const k = Math.pow(d, 1.6);
                    let col = k < 0.5 ? mix(deep, navy, k / 0.5) : mix(navy, steel, (k - 0.5) / 0.5);
                    if (d > 0.9) col = mix(col, gold, (d - 0.9) / 0.1 * 0.85);
                    if (d > 0.97) col = mix(col, shine, (d - 0.97) / 0.03 * 0.6);
                    col = mix(col, gold, rim * 0.35);
                    ctx.beginPath();
                    ctx.moveTo(proj[f[0]][0], proj[f[0]][1]);
                    ctx.lineTo(proj[f[1]][0], proj[f[1]][1]);
                    ctx.lineTo(proj[f[2]][0], proj[f[2]][1]);
                    ctx.closePath();
                    ctx.fillStyle = `rgb(${col[0]},${col[1]},${col[2]})`;
                    ctx.fill();
                    ctx.strokeStyle = `rgba(228,189,98,${0.22 + d * 0.35})`;
                    ctx.lineWidth = 0.8;
                    ctx.stroke();
                });

                // Orbiting data points around the stone
                for (let i = 0; i < 46; i++) {
                    const a = i * 2.39996 + time / 9000;
                    const rr = R * (1.25 + (i % 7) * 0.09);
                    const px = ox + Math.cos(a) * rr;
                    const py = oy + Math.sin(a) * rr * 0.42 + Math.sin(a * 2 + i) * 6;
                    const front = Math.sin(a) > 0;
                    ctx.fillStyle = front ? 'rgba(255,255,255,0.7)' : 'rgba(228,189,98,0.4)';
                    ctx.beginPath(); ctx.arc(px, py, front ? 1.6 : 1.1, 0, Math.PI * 2); ctx.fill();
                }
            }
            requestAnimationFrame(draw);
        };

        resize();
        window.addEventListener('resize', resize);
        if (finePointer) {
            window.addEventListener('pointermove', (e) => {
                targetLeanX = (e.clientX / window.innerWidth - 0.5) * 2;
                targetLeanY = (e.clientY / window.innerHeight - 0.5) * 2;
            }, { passive: true });
        }
        if ('IntersectionObserver' in window) {
            new IntersectionObserver((en) => { visible = en[0].isIntersecting; }).observe(stoneCanvas);
        }
        document.addEventListener('visibilitychange', () => {
            running = !document.hidden;
            if (running) requestAnimationFrame(draw);
        });
        requestAnimationFrame(draw);
    }

    /* ---------- Lattice: drifting gold points for inner page headers ---------- */
    $$('[data-lattice]').forEach((canvas) => {
        const ctx = canvas.getContext('2d');
        if (!ctx) return;
        let W, H, pts = [], dpr = 1, mouse = { x: -999, y: -999 }, visible = true;
        const density = parseFloat(canvas.dataset.density || '1');
        const init = () => {
            const r = canvas.getBoundingClientRect();
            dpr = Math.min(window.devicePixelRatio || 1, 2);
            W = r.width; H = r.height;
            canvas.width = W * dpr; canvas.height = H * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            const count = Math.round((W * H) / 22000 * density);
            pts = Array.from({ length: count }, () => ({
                x: W * (0.55 + Math.random() * 0.45), y: Math.random() * H,
                vx: (Math.random() - 0.5) * 0.25, vy: (Math.random() - 0.5) * 0.25,
            }));
        };
        const step = () => {
            if (visible) {
                ctx.clearRect(0, 0, W, H);
                for (const p of pts) {
                    if (!reduced) { p.x += p.vx; p.y += p.vy; }
                    if (p.x < W * 0.5 || p.x > W) p.vx *= -1;
                    if (p.y < 0 || p.y > H) p.vy *= -1;
                }
                for (let i = 0; i < pts.length; i++) {
                    const a = pts[i];
                    for (let j = i + 1; j < pts.length; j++) {
                        const b = pts[j];
                        const d = Math.hypot(a.x - b.x, a.y - b.y);
                        if (d < 130) {
                            ctx.strokeStyle = `rgba(23,64,110,${(1 - d / 130) * 0.16})`;
                            ctx.lineWidth = 0.7;
                            ctx.beginPath(); ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.stroke();
                        }
                    }
                    const md = Math.hypot(a.x - mouse.x, a.y - mouse.y);
                    ctx.fillStyle = md < 140 ? 'rgba(200,149,31,.9)' : 'rgba(23,64,110,.28)';
                    ctx.beginPath(); ctx.arc(a.x, a.y, md < 140 ? 2.2 : 1.4, 0, Math.PI * 2); ctx.fill();
                }
            }
            requestAnimationFrame(step);
        };
        init();
        window.addEventListener('resize', init);
        canvas.parentElement.parentElement.addEventListener('pointermove', (e) => {
            const r = canvas.getBoundingClientRect(); mouse = { x: e.clientX - r.left, y: e.clientY - r.top };
        });
        if ('IntersectionObserver' in window) new IntersectionObserver((en) => { visible = en[0].isIntersecting; }).observe(canvas);
        requestAnimationFrame(step);
    });
})();
