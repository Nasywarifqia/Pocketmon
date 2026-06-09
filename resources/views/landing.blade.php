<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketMon - Kelola Keuanganmu dengan Mudah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #F8F9FA; }

        /* Navbar */
        .navbar { position: fixed; top: 0; width: 100%; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); box-shadow: 0 1px 20px rgba(0,0,0,0.06); z-index: 100; padding: 16px 0; }
        .navbar-inner { max-width: 1100px; margin: 0 auto; padding: 0 32px; display: flex; align-items: center; justify-content: space-between; }
        .logo { display: flex; align-items: center; gap: 10px; }
        .logo-icon { width: 40px; height: 40px; background: #F8D7DA; border-radius: 14px; display: flex; align-items: center; justify-content: center; }
        .logo-icon img { width: 28px; height: 28px; object-fit: contain; }
        .logo-text { font-weight: 700; font-size: 18px; color: #1a1a2e; }
        .nav-buttons { display: flex; align-items: center; gap: 12px; }
        .btn-login { padding: 8px 20px; font-size: 14px; font-weight: 500; color: #6b7280; text-decoration: none; border-radius: 12px; transition: all 0.2s; }
        .btn-login:hover { color: #e879a0; }
        .btn-register { padding: 8px 20px; font-size: 14px; font-weight: 600; color: #be185d; background: #F8D7DA; border-radius: 12px; text-decoration: none; transition: all 0.2s; }
        .btn-register:hover { background: #fbb6ce; }

        /* Hero */
        .hero { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 100px 32px 60px; background: linear-gradient(135deg, #fff1f3 0%, #f5f0ff 50%, #f0fff4 100%); }
        .hero-inner { max-width: 700px; margin: 0 auto; text-align: center; }
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; background: white; border-radius: 100px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); font-size: 13px; color: #e879a0; font-weight: 500; margin-bottom: 32px; }
        .hero-title { font-size: 52px; font-weight: 800; color: #1a1a2e; line-height: 1.2; margin-bottom: 20px; }
        .hero-title span { background: linear-gradient(135deg, #f4a0b0, #c9a0dc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero-desc { font-size: 17px; color: #6b7280; line-height: 1.8; margin-bottom: 40px; }
        .hero-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; margin-bottom: 60px; }
        .btn-primary { padding: 16px 36px; font-size: 15px; font-weight: 600; color: white; background: linear-gradient(135deg, #f4a0b0, #c9a0dc); border-radius: 16px; text-decoration: none; box-shadow: 0 8px 24px rgba(244,160,176,0.4); transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(244,160,176,0.5); }
        .btn-secondary { padding: 16px 36px; font-size: 15px; font-weight: 600; color: #4b5563; background: white; border-radius: 16px; text-decoration: none; box-shadow: 0 4px 16px rgba(0,0,0,0.08); transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; border: 1px solid #f3f4f6; }
        .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .hero-stats { display: flex; gap: 0; background: white; border-radius: 20px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); overflow: hidden; max-width: 420px; margin: 0 auto; }
        .stat-item { flex: 1; padding: 20px 16px; text-align: center; border-right: 1px solid #f3f4f6; }
        .stat-item:last-child { border-right: none; }
        .stat-num { font-size: 24px; font-weight: 700; color: #1a1a2e; }
        .stat-label { font-size: 12px; color: #9ca3af; margin-top: 4px; }

        /* Features */
        .features { padding: 80px 32px; background: white; }
        .features-inner { max-width: 1100px; margin: 0 auto; }
        .section-title { text-align: center; margin-bottom: 60px; }
        .section-title h2 { font-size: 36px; font-weight: 700; color: #1a1a2e; margin-bottom: 12px; }
        .section-title p { font-size: 16px; color: #9ca3af; max-width: 500px; margin: 0 auto; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .feature-card { padding: 28px; background: #F8F9FA; border-radius: 24px; transition: all 0.3s; }
        .feature-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.08); transform: translateY(-4px); }
        .feature-icon { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; font-size: 22px; }
        .feature-card h3 { font-size: 16px; font-weight: 600; color: #1a1a2e; margin-bottom: 8px; }
        .feature-card p { font-size: 14px; color: #9ca3af; line-height: 1.7; }

        /* CTA Section */
        .cta-section { padding: 80px 32px; background: linear-gradient(135deg, #f4a0b0 0%, #c9a0dc 100%); text-align: center; }
        .cta-section h2 { font-size: 36px; font-weight: 700; color: white; margin-bottom: 12px; }
        .cta-section p { font-size: 16px; color: rgba(255,255,255,0.75); margin-bottom: 32px; }
        .btn-cta { display: inline-flex; align-items: center; gap: 10px; padding: 16px 40px; background: white; color: #e879a0; font-weight: 700; font-size: 15px; border-radius: 16px; text-decoration: none; box-shadow: 0 8px 32px rgba(0,0,0,0.15); transition: all 0.3s; }
        .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.2); }

        /* Footer */
        .footer { background: white; padding: 32px; text-align: center; }
        .footer-logo { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 8px; }
        .footer-logo-icon { width: 32px; height: 32px; background: #F8D7DA; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .footer-logo-icon img { width: 22px; height: 22px; object-fit: contain; }
        .footer p { font-size: 13px; color: #9ca3af; }

        @media (max-width: 768px) {
            .hero-title { font-size: 36px; }
            .features-grid { grid-template-columns: 1fr; }
            .hero-buttons { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar">
        <div class="navbar-inner">
            <div class="logo">
                <div class="logo-icon">
                    <img src="{{ asset('images/logo.png') }}" alt="PocketMon">
                </div>
                <span class="logo-text">PocketMon</span>
            </div>
            <div class="nav-buttons">
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-register">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-badge">
                Aplikasi Keuangan Pribadi untuk Anda
            </div>
            <h1 class="hero-title">
                Kelola Keuanganmu<br>
                <span>Lebih Cerdas</span>
            </h1>
            <p class="hero-desc">
                PocketMon membantu kamu mencatat pemasukan, pengeluaran,<br>
                memantau saldo wallet, dan merencanakan tabungan —<br>
                semua dalam satu platform yang simpel dan menyenangkan.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-primary">
                    <i class="fa-solid fa-rocket"></i>
                    Mulai Gratis Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-secondary">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Sudah Punya Akun
                </a>
                <a href="{{ route('guest.dashboard') }}" class="btn-secondary">
                    <i class="fa-solid fa-eye"></i>
                    Coba Sebagai Tamu
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Gratis</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">7+</div>
                    <div class="stat-label">Fitur Lengkap</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">Easy</div>
                    <div class="stat-label">Mudah Dipakai</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="features">
        <div class="features-inner">
            <div class="section-title">
                <h2>Semua yang Kamu Butuhkan</h2>
                <p>Fitur lengkap untuk bantu kamu kelola keuangan sehari-hari dengan lebih terorganisir</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background: #D1E7DD;">
                        <i class="fa-solid fa-wallet" style="color: #16a34a;"></i>
                    </div>
                    <h3>Multi Wallet</h3>
                    <p>Kelola beberapa akun sekaligus — cash, bank, e-wallet, dan kartu kredit dalam satu tempat.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background: #F8D7DA;">
                        <i class="fa-solid fa-arrow-trend-up" style="color: #e11d48;"></i>
                    </div>
                    <h3>Catat Transaksi</h3>
                    <p>Catat pemasukan dan pengeluaran dengan mudah, lengkap dengan kategori dan deskripsi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background: #E2D9F3;">
                        <i class="fa-solid fa-vault" style="color: #7c3aed;"></i>
                    </div>
                    <h3>Brankas Tabungan</h3>
                    <p>Buat target tabungan dengan nama, nominal, dan deadline. Pantau progress-nya secara real-time.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background: #F8D7DA;">
                        <i class="fa-solid fa-chart-pie" style="color: #e11d48;"></i>
                    </div>
                    <h3>Laporan Keuangan</h3>
                    <p>Lihat laporan bulanan dengan grafik pemasukan dan pengeluaran yang mudah dipahami.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background: #D1E7DD;">
                        <i class="fa-solid fa-clock-rotate-left" style="color: #16a34a;"></i>
                    </div>
                    <h3>Riwayat Transaksi</h3>
                    <p>Lihat semua riwayat transaksi dengan fitur filter, pencarian, dan sorting yang lengkap.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background: #E2D9F3;">
                        <i class="fa-solid fa-right-left" style="color: #7c3aed;"></i>
                    </div>
                    <h3>Transfer Antar Wallet</h3>
                    <p>Pindahkan saldo antar wallet dengan mudah dan semua tercatat otomatis.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cta-section">
        <h2>Mulai Kelola Keuanganmu Sekarang</h2>
        <p>Gratis dan dapat diakses kapanpun anda inginkan.</p>
        <a href="{{ route('register') }}" class="btn-cta">
            <i class="fa-solid fa-rocket"></i>
            Daftar Sekarang — Gratis!
        </a>
    </section>

    {{-- Footer --}}
    <footer class="footer">
        <div class="footer-logo">
            <div class="footer-logo-icon">
                <img src="{{ asset('images/logo.png') }}" alt="PocketMon">
            </div>
            <span style="font-weight: 700; color: #374151;">PocketMon</span>
        </div>
        <p>© 2026 PocketMon. Final Project Pemrograman Website.</p>
    </footer>

</body>
</html>