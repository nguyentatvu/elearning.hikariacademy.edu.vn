<div id="audio_waveform">
    <style>
        :root {
            --dot-size: 1.25rem;
            --max-block-size: calc(var(--dot-size) * 5);
            --dot-color: #166bc9;
            --dot-color-transition-1: #66a9db;
            --dot-color-transition-2: #b3d9ef;
            --delay: 0ms;
        }

        .audio-waveform-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        h2 {
            font-size: 1.75rem;
            color: #166bc9;
            text-align: center;
        }

        .loader {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: calc(var(--dot-size) / 2);
            block-size: var(--max-block-size);
        }

        .dot {
            inline-size: var(--dot-size);
            block-size: var(--dot-size);
            border-radius: calc(var(--dot-size) / 2);
            background: var(--dot-color);
            animation: y-grow 2s infinite ease-in-out;
            animation-delay: calc(var(--delay) * 1ms);
        }

        @keyframes y-grow {
            25% {
                block-size: var(--max-block-size);
                background-color: var(--dot-color-transition-1);
            }

            50% {
                block-size: var(--dot-size);
                background-color: var(--dot-color-transition-2);
            }
        }
    </style>

    <main class="audio-waveform-container">
        <h2 id="audio_waveform_title"></h2>
        <div class="loader js-loader" data-delay="200">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </main>

    <script>
        const loader = document.querySelector(".loader");
        const delay = +loader.dataset.delay || 200;
        const dots = loader.querySelectorAll(".loader .dot");
        dots.forEach((dot, index) => {
            dot.style = `--delay: ${delay * index}`;
        });
    </script>
</div>
