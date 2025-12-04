// White Noise Player for Focus - Usando YouTube
class WhiteNoisePlayer {
    constructor() {
        this.youtubePlayer = null;
        this.isPlaying = false;
        this.volume = 30; // Volume padrão (30%)
        this.videoId = 'vweEQFJA3w8'; // ID do vídeo do YouTube
        this.init();
    }

    init() {
        // Carregar estado salvo
        const savedState = localStorage.getItem('whiteNoiseState');
        if (savedState) {
            const state = JSON.parse(savedState);
            this.volume = state.volume || 30;
            this.isPlaying = state.isPlaying || false;
        }

        // Carregar API do YouTube
        this.loadYouTubeAPI();
        
        // Criar interface
        this.createUI();
    }

    loadYouTubeAPI() {
        // Verificar se a API já foi carregada
        if (window.YT && window.YT.Player) {
            setTimeout(() => this.initYouTubePlayer(), 100);
            return;
        }

        // Salvar referência para o callback
        const originalCallback = window.onYouTubeIframeAPIReady;
        window.onYouTubeIframeAPIReady = () => {
            if (originalCallback) originalCallback();
            this.initYouTubePlayer();
        };

        // Verificar se o script já está sendo carregado
        const existingScript = document.querySelector('script[src*="youtube.com/iframe_api"]');
        if (existingScript) {
            return;
        }

        // Carregar script da API do YouTube
        const tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    initYouTubePlayer() {
        // Verificar se o container já existe
        let iframeContainer = document.getElementById('youtube-player-container');
        if (!iframeContainer) {
            // Criar iframe do YouTube (oculto)
            iframeContainer = document.createElement('div');
            iframeContainer.id = 'youtube-player-container';
            iframeContainer.style.cssText = 'position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; left: -9999px;';
            document.body.appendChild(iframeContainer);
        }

        // Verificar se o player já foi inicializado
        if (this.youtubePlayer) {
            return;
        }

        try {
            this.youtubePlayer = new YT.Player('youtube-player-container', {
                videoId: this.videoId,
                playerVars: {
                    autoplay: 0,
                    loop: 1,
                    playlist: this.videoId, // Necessário para loop funcionar
                    controls: 0,
                    disablekb: 1,
                    enablejsapi: 1,
                    iv_load_policy: 3,
                    modestbranding: 1,
                    playsinline: 1,
                    rel: 0,
                    mute: 0, // Não mutar por padrão
                    origin: window.location.origin
                },
                events: {
                    onReady: (event) => {
                        console.log('YouTube player pronto');
                        // Garantir que não está mudo e definir volume
                        event.target.unMute();
                        event.target.setVolume(this.volume);
                        // Aguardar um pouco antes de tentar tocar
                        if (this.isPlaying) {
                            setTimeout(() => {
                                this.start();
                            }, 1000);
                        }
                    },
                    onStateChange: (event) => {
                        console.log('Estado do player:', event.data);
                        // Manter o vídeo tocando em loop
                        if (event.data === YT.PlayerState.ENDED) {
                            event.target.playVideo();
                        }
                        // Se o vídeo foi pausado por erro, tentar novamente
                        if (event.data === YT.PlayerState.PAUSED && this.isPlaying) {
                            setTimeout(() => {
                                if (this.isPlaying) {
                                    event.target.unMute();
                                    event.target.playVideo();
                                }
                            }, 500);
                        }
                        // Se o vídeo está tocando, garantir que não está mudo
                        if (event.data === YT.PlayerState.PLAYING) {
                            event.target.unMute();
                        }
                    },
                    onError: (event) => {
                        console.error('Erro no YouTube player:', event.data);
                    }
                }
            });
        } catch (e) {
            console.error('Erro ao criar YouTube player:', e);
        }
    }

    createUI() {
        // Criar container do player
        const playerContainer = document.createElement('div');
        playerContainer.id = 'white-noise-player';
        playerContainer.innerHTML = `
            <div class="wn-header">
                <i class="fas fa-headphones"></i>
                <span>Ruído Branco</span>
                <button class="wn-toggle" id="wn-toggle">
                    <i class="fas fa-power-off"></i>
                </button>
            </div>
            <div class="wn-controls" id="wn-controls">
                <div class="wn-volume-control">
                    <i class="fas fa-volume-down"></i>
                    <input type="range" id="wn-volume" min="0" max="100" value="${this.volume}" class="wn-slider">
                    <i class="fas fa-volume-up"></i>
                </div>
                <div class="wn-info">
                    <small>Para melhor foco na leitura</small>
                </div>
            </div>
        `;
        document.body.appendChild(playerContainer);

        // Event listeners
        document.getElementById('wn-toggle').addEventListener('click', () => this.toggle());
        document.getElementById('wn-volume').addEventListener('input', (e) => this.setVolume(parseInt(e.target.value)));
    }

    start() {
        if (this.isPlaying) return;

        if (!this.youtubePlayer) {
            // Se o player ainda não está pronto, aguardar
            if (window.YT && window.YT.Player) {
                this.initYouTubePlayer();
                // Aguardar o player estar pronto
                let tentativas = 0;
                const checkReady = setInterval(() => {
                    tentativas++;
                    if (this.youtubePlayer && typeof this.youtubePlayer.getPlayerState === 'function') {
                        clearInterval(checkReady);
                        setTimeout(() => this.start(), 500);
                    } else if (tentativas > 50) { // Timeout após 5 segundos
                        clearInterval(checkReady);
                        console.error('Timeout ao aguardar YouTube player');
                    }
                }, 100);
                return;
            } else {
                console.error('YouTube API não carregada');
                return;
            }
        }

        try {
            // Verificar se o player está pronto
            const state = this.youtubePlayer.getPlayerState();
            console.log('Estado atual do player:', state);
            
            // Garantir que não está mudo ANTES de tocar
            this.youtubePlayer.unMute();
            this.youtubePlayer.setVolume(this.volume);
            
            if (state === YT.PlayerState.UNSTARTED || state === YT.PlayerState.PAUSED || state === YT.PlayerState.CUED) {
                this.youtubePlayer.playVideo();
                this.isPlaying = true;
                this.saveState();
                this.updateUI();
                
                // Verificar após um tempo se realmente está tocando
                setTimeout(() => {
                    const newState = this.youtubePlayer.getPlayerState();
                    if (newState !== YT.PlayerState.PLAYING && this.isPlaying) {
                        console.log('Vídeo não iniciou, tentando novamente...');
                        this.youtubePlayer.unMute();
                        this.youtubePlayer.playVideo();
                    }
                }, 1000);
            } else if (state === YT.PlayerState.PLAYING) {
                // Já está tocando, apenas atualizar UI
                this.isPlaying = true;
                this.updateUI();
            }
        } catch (e) {
            console.error('Erro ao iniciar vídeo:', e);
            // Tentar novamente após um tempo
            setTimeout(() => {
                if (!this.isPlaying) {
                    this.start();
                }
            }, 1000);
        }
    }

    stop() {
        if (!this.isPlaying || !this.youtubePlayer) return;

        try {
            this.youtubePlayer.pauseVideo();
            this.isPlaying = false;
            this.saveState();
            this.updateUI();
        } catch (e) {
            console.error('Erro ao parar vídeo:', e);
        }
    }

    toggle() {
        if (this.isPlaying) {
            this.stop();
        } else {
            this.start();
        }
    }

    setVolume(value) {
        this.volume = Math.max(0, Math.min(100, value));
        if (this.youtubePlayer) {
            try {
                this.youtubePlayer.setVolume(this.volume);
                // Se o volume for 0, mutar; caso contrário, desmutar
                if (this.volume === 0) {
                    this.youtubePlayer.mute();
                } else {
                    this.youtubePlayer.unMute();
                }
            } catch (e) {
                console.error('Erro ao definir volume:', e);
            }
        }
        this.saveState();
    }

    updateUI() {
        const toggle = document.getElementById('wn-toggle');
        const player = document.getElementById('white-noise-player');
        const controls = document.getElementById('wn-controls');
        
        if (this.isPlaying) {
            toggle.classList.add('active');
            player.classList.add('playing');
            controls.style.display = 'block';
        } else {
            toggle.classList.remove('active');
            player.classList.remove('playing');
            controls.style.display = 'none';
        }
    }

    saveState() {
        localStorage.setItem('whiteNoiseState', JSON.stringify({
            isPlaying: this.isPlaying,
            volume: this.volume
        }));
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    window.whiteNoisePlayer = new WhiteNoisePlayer();
});

