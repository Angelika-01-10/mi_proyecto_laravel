@extends('layouts.app')

@section('styles')
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(to right, #fce7f3, #fff1f2);
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 3rem 1rem;
}
.hero-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    max-width: 1280px;
    margin: 0 auto;
    align-items: center;
}

/* Títulos y textos */
.hero-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #111827;
    line-height: 1.2;
    margin-bottom: 1rem;
}
.hero-title span {
    color: #db2777;
}
.hero-text {
    font-size: 1.125rem;
    color: #4b5563;
    margin-bottom: 2rem;
    max-width: 600px;
}

/* Botones */
.hero-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}
.hero-btn-primary {
    background: #db2777;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 1.125rem;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: background 0.3s;
}
.hero-btn-primary:hover {
    background: #be185d;
}
.hero-btn-secondary {
    border: 1px solid #d1d5db;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    background: white;
    color: #374151;
    font-size: 1.125rem;
    text-decoration: none;
    transition: background 0.3s;
}
.hero-btn-secondary:hover {
    background: #f3f4f6;
}

/* Features */
.hero-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-top: 2rem;
}
.feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.feature-icon {
    background: #fce7f3;
    color: #db2777;
    padding: 0.5rem;
    border-radius: 9999px;
    font-size: 1.5rem;
}

/* Imagen */
.hero-image {
    position: relative;
}
.image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
    box-shadow: 0 10px 15px rgba(0,0,0,0.1);
}
.image-wrapper img {
    width: 100%;
    height: 500px;
    object-fit: cover;
}
.image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(251,207,232,0.3), transparent);
}

/* Stats flotantes */
.stat-box {
    position: absolute;
    background: white;
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}
.stat-left {
    bottom: -1.5rem;
    left: -1.5rem;
}
.stat-right {
    top: -1.5rem;
    right: -1.5rem;
}
.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #db2777;
}
.stat-text {
    font-size: 0.875rem;
    color: #6b7280;
    text-align: center;
}

#tipos-piel {
    padding: 4rem 1rem;
    background-color: #fff1f2;
}

/* Encabezado */
#tipos-piel .section-title {
    font-size: 2rem;
    font-weight: bold;
    color: #111827;
    text-align: center;
    margin-bottom: 0.5rem;
}
#tipos-piel .section-subtitle {
    font-size: 1rem;
    color: #4b5563;
    text-align: center;
    margin-bottom: 3rem;
}

/* Grid de tarjetas */
.skin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

/* Tarjeta */
.skin-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}
.skin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.skin-card-icon {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto 1.5rem;
    box-shadow: 0 10px 22px rgba(0,0,0,0.15);
    border: 6px solid #fce7f3;
}

.skin-card-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

/* Títulos */
.skin-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}
.skin-card p {
    font-size: 0.9rem;
    color: #4b5563;
    margin-bottom: 1rem;
}
.skin-card h4 {
    font-size: 1rem;
    font-weight: 500;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}
.skin-card ul {
    font-size: 0.875rem;
    color: #374151;
    text-align: left;
    list-style: none;
    padding: 0;
}
.skin-card ul li {
    margin-bottom: 0.25rem;
}

/* Test de piel */
#card-content button {
    display: block;
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    text-align: left;
    background: white;
    cursor: pointer;
    margin-bottom: 0.75rem;
    transition: background 0.2s;
}
#card-content button:hover {
    background: #fce7f3;
}
.question-text {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #111827;
}
.question-image img {
    width: 100%;
    max-height: 250px;
    object-fit: cover;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}
</style>
@endsection

@section('hero')
<section id="inicio" class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">
                Tu piel en las mejores <span>manos</span>
            </h1>
            <p class="hero-text">
                Conecta con dermatólogos especializados, aprende sobre el cuidado de tu piel y únete a nuestra comunidad de bienestar dermatológico.
            </p>

            <div class="hero-buttons">
                <a href="#test" class="hero-btn-primary">
                    Comenzar Test de Piel
                </a>
                <a href="#tipos-piel" class="hero-btn-secondary">
                    Explorar Consejos
                </a>
            </div>

            <div class="hero-features">
                <div class="feature-item">
                    <div class="feature-icon">❤</div>
                    <div>
                        <h3>Cuidado Personal</h3>
                        <p>Rutinas adaptadas a tu piel</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">👩‍⚕</div>
                    <div>
                        <h3>Especialistas</h3>
                        <p>Dermatólogos certificados</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🛡</div>
                    <div>
                        <h3>Seguridad</h3>
                        <p>Datos protegidos</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-image">
            <div class="image-wrapper">
                <img src="{{ asset('images/hero-dermatology.jpg') }}" alt="Cuidado dermatológico profesional">
                <div class="image-overlay"></div>
            </div>

            <div class="stat-box stat-left">
                <div class="text-center">
                    <div class="stat-number">1000+</div>
                    <div class="stat-text">Pacientes atendidos</div>
                </div>
            </div>
            <div class="stat-box stat-right">
                <div class="text-center">
                    <div class="stat-number">24/7</div>
                    <div class="stat-text">Consultas disponibles</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="dashboard-content">
    <section id="tipos-piel">
        <h2 class="section-title">Conoce tu Tipo de Piel</h2>
        <p class="section-subtitle">
            Identificar tu tipo de piel es el primer paso para un cuidado efectivo y personalizado.
        </p>

        <div class="skin-grid">
            <!-- Piel Grasa -->
            <div class="skin-card">
                <div class="skin-card-icon">
                    <img src="{{ asset('images/grasa.jpeg') }}" alt="Piel Grasa">
                </div>
                <h3>Piel Grasa</h3>
                <p>Brillante, poros dilatados, tendencia al acné</p>
                <h4>Consejos rápidos:</h4>
                <ul>
                    <li>• Limpieza suave 2x día</li>
                    <li>• Usa productos oil-free</li>
                    <li>• Hidratación ligera</li>
                </ul>
            </div>

            <!-- Piel Seca -->
            <div class="skin-card">
                <div class="skin-card-icon">
                    <img src="{{ asset('images/seca.jpeg') }}" alt="Piel Seca">
                </div>
                <h3>Piel Seca</h3>
                <p>Tirante, descamación, falta de elasticidad</p>
                <h4>Consejos rápidos:</h4>
                <ul>
                    <li>• Hidratación intensa</li>
                    <li>• Evita agua muy caliente</li>
                    <li>• Protector solar diario</li>
                </ul>
            </div>

            <!-- Piel Mixta -->
            <div class="skin-card">
                <div class="skin-card-icon">
                    <img src="{{ asset('images/mixta.jpeg') }}" alt="Piel Mixta">
                </div>
                <h3>Piel Mixta</h3>
                <p>Grasa en zona T, seca en mejillas</p>
                <h4>Consejos rápidos:</h4>
                <ul>
                    <li>• Cuidado por zonas</li>
                    <li>• Limpieza equilibrada</li>
                    <li>• Hidratación adaptada</li>
                </ul>
            </div>

            <!-- Piel Sensible -->
            <div class="skin-card">
                <div class="skin-card-icon">
                    <img src="{{ asset('images/sensible.jpeg') }}" alt="Piel Sensible">
                </div>
                <h3>Piel Sensible</h3>
                <p>Reactiva, enrojecimiento, irritación fácil</p>
                <h4>Consejos rápidos:</h4>
                <ul>
                    <li>• Productos hipoalergénicos</li>
                    <li>• Evita fragancias</li>
                    <li>• Protección solar mineral</li>
                </ul>
            </div>
        </div>
    </section>

  <meta name="csrf-token" content="{{ csrf_token() }}">

<section id="test" class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Analiza tu Piel</h2>
        <p class="text-gray-600 mb-6">
            Sube una foto de tu rostro y descubre automáticamente tu tipo de piel junto con recomendaciones personalizadas.
        </p>

        <div id="test-card" class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h3 id="card-title" class="text-xl font-semibold mb-4">Sube tu foto</h3>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                <div id="progress-bar" class="bg-pink-500 h-2 rounded-full w-0 transition-all duration-500"></div>
            </div>

            <button id="upload-btn" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 mb-4">
                Seleccionar imagen
            </button>
            <input id="upload-input" type="file" accept="image/*" style="display:none;">

            <button id="submit-btn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mb-4">
                Analizar Imagen
            </button>

            <div id="preview" class="mb-4"></div>
            <div id="card-content"></div>
        </div>
    </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const uploadBtn = document.getElementById('upload-btn');
    const uploadInput = document.getElementById('upload-input');
    const submitBtn = document.getElementById('submit-btn');
    const preview = document.getElementById('preview');
    const cardContent = document.getElementById('card-content');
    const progressBar = document.getElementById('progress-bar');

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    uploadBtn.addEventListener('click', () => uploadInput.click());

    uploadInput.addEventListener('change', () => {
        const file = uploadInput.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = () => {
            preview.innerHTML = `<img src="${reader.result}" class="mx-auto mb-4 rounded-lg" style="max-height:250px;">`;
        };
        reader.readAsDataURL(file);
        cardContent.innerHTML = '';
        progressBar.style.width = '0%';
    });

    submitBtn.addEventListener('click', async () => {
        const file = uploadInput.files[0];
        if (!file) return alert("Selecciona una imagen primero.");

        const formData = new FormData();
        formData.append('image', file);

        progressBar.style.width = '0%';
        cardContent.innerHTML = '<p class="text-gray-500">Analizando tu piel...</p>';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Analizando...';

        try {
            const res = await fetch('/analyze-skin', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });

            const result = await res.json();
            progressBar.style.width = '100%';
            submitBtn.disabled = false;
            submitBtn.textContent = 'Analizar Imagen';

            if(res.ok){
                cardContent.innerHTML = `
                    <h3 class="text-lg font-semibold">Resultado:</h3>
                    <p>Tipo de piel detectado: <strong>${result.type}</strong></p>
                    ${result.probabilities ? `<pre>${JSON.stringify(result.probabilities, null, 2)}</pre>` : ''}
                `;
            } else {
                cardContent.innerHTML = `<p class="text-red-500 font-semibold">Error: ${result.error}</p>
                                         ${result.details ? `<pre>${result.details}</pre>` : ''}`;
            }
        } catch(err){
            cardContent.innerHTML = `<p class="text-red-500 font-semibold">Error al analizar la imagen</p>
                                     <pre>${err.message}</pre>`;
            submitBtn.disabled = false;
            submitBtn.textContent = 'Analizar Imagen';
        }
    });
});

</script>


@endsection
