<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - InfoMotive</title>
    <!-- Use main style -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-cyan: #00E5FF;
            --primary-blue: #00AEEF;
        }

        body {
            background-color: #0c0c0c;
            color: white;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            /* Mouse Glow Background Base */
            background-image: radial-gradient(circle at 50% 50%, rgba(0, 229, 255, 0.03) 0%, rgba(12, 12, 12, 0) 50%);
            background-attachment: fixed;
        }

        /* Particle Canvas */
        #bg-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Behind everything */
            pointer-events: none;
        }

        /* Typewriter Effect */
        .typewriter {
            display: inline-block;
            overflow: hidden;
            border-right: 3px solid #00E5FF;
            white-space: nowrap;
            margin: 0 auto;
            animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
            max-width: 100%;
        }

        @keyframes typing {
            from { width: 0; opacity: 0; }
            to { width: 100%; opacity: 1; }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #00E5FF; }
        }

        /* --- ANIMATIONS --- */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mouse Glow Overlay */
        .cursor-glow {
            position: fixed;
            top: 0;
            left: 0;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0, 229, 255, 0.08) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
            mix-blend-mode: screen;
        }

        /* Glassmorphism Navbar */
        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 80px;
            background: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            z-index: 1000;
        }
        .navbar-custom .container-nav {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
            box-sizing: border-box;
        }
        .brand-logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        .brand-logo span { color: #00E5FF; }
        
        .nav-links-custom {
            display: flex;
            align-items: center;
            gap: 35px;
        }
        .nav-links-custom a {
            color: #a1a1aa;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .nav-links-custom a:hover, .nav-links-custom a.active {
            color: #00E5FF;
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.4);
        }
        .btn-login-custom {
            background: linear-gradient(135deg, #00AEEF 0%, #00E5FF 100%);
            color: #0a0a0a !important;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0, 229, 255, 0.3);
            transition: all 0.3s ease;
        }
        .btn-login-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 229, 255, 0.5);
        }

        /* Robust Footer */
        .footer-custom {
            background: #0d0d0f;
            border-top: 1px solid #1a1a1f;
            padding: 80px 50px 40px 50px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            text-align: left;
            margin-top: 80px;
        }
        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 50px;
            margin-bottom: 60px;
        }
        .footer-brand h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            margin: 0 0 20px 0;
        }
        .footer-brand h3 span { color: #00E5FF; }
        .footer-brand p {
            color: #71717a;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }
        .footer-heading {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin: 0 0 25px 0;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .footer-links a {
            color: #a1a1aa;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }
        .footer-links a:hover { color: #00E5FF; }
        
        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid #1a1a1f;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #71717a;
            font-size: 0.85rem;
        }
        .social-icons {
            display: flex;
            gap: 20px;
        }
        .social-icons a {
            color: #71717a;
            font-size: 1.2rem;
            transition: color 0.2s ease;
        }
        .social-icons a:hover { color: #00E5FF; }

        /* About Header */
        .about-header {
            text-align: center;
            padding: 80px 20px;
            background: linear-gradient(180deg, rgba(0,174,239,0.1) 0%, rgba(12,12,12,0) 100%);
        }
        .about-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        .about-title span { color: #00E5FF; }
        
        .about-desc {
            max-width: 800px;
            margin: 0 auto;
            color: #aaa;
            line-height: 1.6;
            font-size: 1.1rem;
        }

        /* Content Sections */
        .content-section {
            padding: 60px 50px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .row {
            display: flex;
            align-items: center;
            gap: 50px;
            margin-bottom: 80px;
        }
        .row.reverse { flex-direction: row-reverse; }
        
        .col-text { flex: 1; }
        .col-img { flex: 1; display: flex; justify-content: center;}

        .section-title {
            font-size: 2rem;
            color: white;
            margin-bottom: 20px;
            border-left: 4px solid #00E5FF;
            padding-left: 15px;
        }
        
        .text-p { color: #ccc; line-height: 1.6; margin-bottom: 20px; }

        .img-placeholder {
            display: none; /* Hide old placeholder */
        }
        
        .feature-img {
            width: 100%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            border: 1px solid #333;
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
            /* Idle Float Animation */
            animation: float 6s ease-in-out infinite;
        }
        .feature-img:hover {
            transform: scale(1.02) translateY(-5px);
            border-color: #00E5FF;
            box-shadow: 0 0 25px rgba(0, 229, 255, 0.2);
        }

        /* Team Grid */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        .team-card {
            background: #151515;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #222;
            transition: transform 0.3s;
        }
        .team-card:hover { transform: translateY(-5px); border-color: #00E5FF; }

        .member-avatar {
            width: 100px;
            height: 100px;
            background: #333;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 2rem;
            overflow: hidden; /* Ensure image stays in circle */
            border: 2px solid #00E5FF; /* Added border */
        }
        
        .member-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .member-name { font-size: 1.2rem; font-weight: 700; color: white; margin-bottom: 5px; }
        .member-role { color: #00E5FF; font-size: 0.9rem; }

        /* Footer */
        footer {
            border-top: 1px solid #222;
            padding: 40px;
            text-align: center;
            color: #666;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <!-- PARTICLE BACKGROUND -->
    <canvas id="bg-canvas"></canvas>

    <!-- Glassmorphism Navbar -->
    <nav class="navbar-custom">
        <div class="container-nav">
            <a href="index.php" class="brand-logo">Info<span>Motive</span></a>
            <div class="nav-links-custom">
                <a href="index.php">Home</a>
                <a href="about.php" class="active">About</a>
                <a href="edukasi.php">Edukasi</a>
                <a href="harga.php">Harga</a>
                <a href="bengkel.php">Bengkel</a>
                <a href="auth/login.php" class="btn-login-custom">Login Admin</a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="about-header">
        <h1 class="about-title typewriter">About <span style="color: #00E5FF;">InfoMotive</span></h1>
        <p class="about-desc animate-on-scroll">
            Platform otomotif digital terdepan yang menghubungkan Anda dengan informasi teknis, estimasi harga transparan, dan jaringan bengkel terpercaya di seluruh Indonesia.
        </p>
    </header>

    <div class="content-section">
        
        <!-- Vision Mission -->
        <div class="row">
            <div class="col-text">
                <h2 class="section-title">Visi Kami</h2>
                <p class="text-p">
                    Menjadi ekosistem digital otomotif nomor satu yang memberdayakan pemilik kendaraan dengan informasi yang akurat, transparan, dan mudah diakses, serta membantu pertumbuhan bisnis bengkel lokal melalui teknologi.
                </p>
                <h2 class="section-title">Misi Kami</h2>
                <ul class="text-p" style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><i class="fa-solid fa-check" style="color: #00E5FF; margin-right: 10px;"></i> Menyediakan konten edukasi otomotif yang berkualitas dan mudah dipahami.</li>
                    <li style="margin-bottom: 10px;"><i class="fa-solid fa-check" style="color: #00E5FF; margin-right: 10px;"></i> Menciptakan standar transparansi harga sparepart dan jasa servis.</li>
                    <li><i class="fa-solid fa-check" style="color: #00E5FF; margin-right: 10px;"></i> Membangun komunitas otomotif yang saling mendukung.</li>
                </ul>
            </div>
            <div class="col-img">
                <!-- REPLACE 'vision.png' WITH YOUR ACTUAL FILE NAME IN assets/img/ -->
                <img src="assets/img/team.jpeg" alt="Visi Misi Illustration" class="feature-img">
            </div>
        </div>

        <!-- The Team -->
        <div style="text-align: center; margin-top: 80px;">
            <h2 class="section-title" style="display: inline-block; border: none; font-size: 2.5rem;">Meet The Team</h2>
            <p style="color: #aaa;">Orang-orang di balik layar InfoMotive.</p>
        </div>

        <div class="team-grid">
            <!-- Member 1 -->
            <div class="team-card">
                <div class="member-avatar">
                    <img src="assets/img/Arbi Fadhlurrahman.jpeg" alt="Member 1" onerror="this.onerror=null; this.src='https://via.placeholder.com/150';">
                </div>
                <h3 class="member-name">Arbi Fadhlurrahman</h3>
                <span class="member-role">Project Manager</span>
            </div>
            <!-- Member 2 -->
            <div class="team-card">
                <div class="member-avatar">
                    <img src="assets/img/Taura Rahayudin.jpeg" alt="Member 2" onerror="this.onerror=null; this.src='https://via.placeholder.com/150';">
                </div>
                <h3 class="member-name">Taura Rahayudin</h3>
                <span class="member-role">Frontend Developer</span>
            </div>
             <!-- Member 3 -->
             <div class="team-card">
                <div class="member-avatar">
                   <img src="assets/img/M.gilang romadhon.jpeg" alt="Member 3" onerror="this.onerror=null; this.src='https://via.placeholder.com/150';">
                </div>
                <h3 class="member-name">M.Gilang Romadhon</h3>
                <span class="member-role">Backend Developer</span>
            </div>
             <!-- Member 4 -->
             <div class="team-card">
                <div class="member-avatar">
                   <img src="assets/img/Anggi Adnan Fauzi.jpeg" alt="Member 4" onerror="this.onerror=null; this.src='https://via.placeholder.com/150';">
                </div>
                <h3 class="member-name">Anggi Adnan Fauzi</h3>
                <span class="member-role">UI/UX Designer</span>
            </div>
            <!-- Member 5 -->
            <div class="team-card">
                <div class="member-avatar">
                   <img src="assets/img/Adam Atma Wiguna.jpeg" alt="Member 5" onerror="this.onerror=null; this.src='https://via.placeholder.com/150';">
                </div>
                <h3 class="member-name">Adam Atma Wiguna</h3>
                <span class="member-role">Content Creator</span>
            </div>
        </div>

    </div>

    <!-- Robust Footer -->
    <footer class="footer-custom">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>Info<span>Motive</span></h3>
                <p>Platform otomotif digital terdepan di Indonesia yang menghadirkan transparansi harga suku cadang, edukasi perawatan mendalam, dan direktori bengkel terverifikasi.</p>
            </div>
            <div>
                <h4 class="footer-heading">Navigasi Utama</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="edukasi.php">Edukasi Otomotif</a></li>
                    <li><a href="harga.php">Harga Barang</a></li>
                    <li><a href="bengkel.php">Direktori Bengkel</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-heading">Layanan Pengguna</h4>
                <ul class="footer-links">
                    <li><a href="auth/login.php">Portal Admin</a></li>
                    <li><a href="edukasi.php?category=Tips+%26+Trik">Tips & Trik</a></li>
                    <li><a href="edukasi.php?category=Maintenance">Jadwal Perawatan</a></li>
                    <li><a href="edukasi.php?category=Safety">Panduan Safety</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-heading">Kontak & Dukungan</h4>
                <p style="color: #71717a; font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;">Kami hadir 24/7 untuk menjawab kebutuhan teknis kendaraan Anda melalui asisten pintar kami.</p>
                <div style="color: #00E5FF; font-weight: 700; font-size: 1.1rem;"><i class="fa-solid fa-headset"></i> CS@infomotive.id</div>
            </div>
        </div>
        <div class="footer-bottom">
            <div>&copy; 2026 InfoMotive. All rights reserved.</div>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>
    </footer>

    <!-- GLOW EFFECT ELEMENT -->
    <div class="cursor-glow" id="cursorGlow"></div>

    <script>
        // --- PARTICLE BACKGROUND SYSTEM ---
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];
        
        // Mouse State for Physics
        let mouse = {
            x: null,
            y: null,
            radius: 150 // Radius of influence
        }

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 1.5; // Faster movement
                this.vy = (Math.random() - 0.5) * 1.5; // Faster movement
                this.size = Math.random() * 2 + 1;
                this.density = (Math.random() * 30) + 1; // Mass/Weight
            }
            update() {
                // physics check
                let dx = mouse.x - this.x;
                let dy = mouse.y - this.y;
                let distance = Math.sqrt(dx*dx + dy*dy);
                let forceDirectionX = dx / distance;
                let forceDirectionY = dy / distance;
                let maxDistance = mouse.radius;
                let force = (maxDistance - distance) / maxDistance;
                let directionX = forceDirectionX * force * this.density;
                let directionY = forceDirectionY * force * this.density;

                if (distance < mouse.radius) {
                    // Repulsion: Move away from mouse
                    this.x -= directionX;
                    this.y -= directionY;
                } else {
                    // Constant Movement (Free Floating)
                    this.x += this.vx;
                    this.y += this.vy;
                }

                // Bounce off edges
                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;
            }
            draw() {
                ctx.fillStyle = 'rgba(0, 229, 255, 0.6)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function initParticles() {
            particles = [];
            // Increased density slightly 50 -> 80
            for (let i = 0; i < 80; i++) particles.push(new Particle());
        }

        function animateParticles() {
            ctx.clearRect(0, 0, width, height);
            for (let i = 0; i < particles.length; i++) {
                particles[i].update();
                particles[i].draw();
                
                // Draw connections (Neuron effect)
                for (let j = i; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    
                    // Connect if close
                    if (dist < 150) {
                        ctx.strokeStyle = `rgba(0, 229, 255, ${0.15 - dist/1000})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animateParticles);
        }
        
        initParticles();
        animateParticles();

        // --- MOUSE TRACKING GLOW & PARTICLE INTERACTION ---
        const glow = document.getElementById('cursorGlow');
        document.addEventListener('mousemove', (e) => {
            // Update Glow Position
            glow.style.left = e.clientX + 'px';
            glow.style.top = e.clientY + 'px';
            
            // Update Mouse Position for Particles
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });
        
        // Reset mouse when leaving window
        window.addEventListener('mouseout', () => {
             mouse.x = undefined;
             mouse.y = undefined;
        });

        // --- SCROLL REVEAL ---
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Only animate once
                }
            });
        }, observerOptions);

        // Apply observer to sections and cards
        document.querySelectorAll('.section-title, .text-p, .team-card, .col-img').forEach(el => {
            el.classList.add('animate-on-scroll');
            observer.observe(el);
        });

        // --- HOVER TILT EFFECT FOR TEAM CARDS ---
        document.querySelectorAll('.team-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                // Calculate percentages
                const xPct = x / rect.width - 0.5;
                const yPct = y / rect.height - 0.5;

                // Subtle Tilt
                card.style.transform = `perspective(1000px) rotateX(${yPct * -10}deg) rotateY(${xPct * 10}deg) scale(1.05)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>
