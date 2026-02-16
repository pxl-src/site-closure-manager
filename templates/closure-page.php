<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo esc_html($title); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: <?php echo esc_attr($bg_color); ?>;
            color: <?php echo esc_attr($text_color); ?>;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, <?php echo esc_attr($bg_color); ?> 0%, <?php echo esc_attr($accent_color); ?> 100%);
        }
        
        .closure-container {
            max-width: 600px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo {
            margin-bottom: 40px;
            animation: slideDown 0.6s ease-in-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo img {
            max-width: 200px;
            height: auto;
        }
        
        .icon-wrapper {
            margin-bottom: 30px;
        }
        
        .lock-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(255, 255, 255, 0);
            }
        }
        
        .lock-icon svg {
            width: 40px;
            height: 40px;
            fill: <?php echo esc_attr($text_color); ?>;
        }
        
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .message {
            font-size: 1.2em;
            margin-bottom: 40px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .countdown-wrapper {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }
        
        .countdown-title {
            font-size: 1em;
            margin-bottom: 20px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .countdown {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .countdown-item {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 20px;
            min-width: 80px;
            backdrop-filter: blur(5px);
        }
        
        .countdown-value {
            font-size: 2.5em;
            font-weight: bold;
            display: block;
            line-height: 1;
        }
        
        .countdown-label {
            font-size: 0.8em;
            text-transform: uppercase;
            margin-top: 10px;
            opacity: 0.8;
            letter-spacing: 1px;
        }
        
        .contact-info {
            margin-top: 40px;
            padding-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .contact-info p {
            margin-bottom: 10px;
            opacity: 0.8;
        }
        
        .contact-info a {
            color: <?php echo esc_attr($text_color); ?>;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        
        .contact-info a:hover {
            border-bottom-color: <?php echo esc_attr($text_color); ?>;
            opacity: 1;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            border: none;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }
        
        .social-links svg {
            width: 20px;
            height: 20px;
            fill: <?php echo esc_attr($text_color); ?>;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
            }
            
            .message {
                font-size: 1em;
            }
            
            .countdown-item {
                min-width: 60px;
                padding: 15px;
            }
            
            .countdown-value {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="closure-container">
        <?php if (!empty($logo_url)) : ?>
            <div class="logo">
                <img src="<?php echo esc_url($logo_url); ?>" alt="Logo">
            </div>
        <?php else : ?>
            <div class="icon-wrapper">
                <div class="lock-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9V7zm4 10.723V20h-2v-2.277c-.595-.347-1-.984-1-1.723 0-1.103.897-2 2-2s2 .897 2 2c0 .738-.405 1.376-1 1.723z"/>
                    </svg>
                </div>
            </div>
        <?php endif; ?>
        
        <h1><?php echo esc_html($title); ?></h1>
        
        <div class="message">
            <?php echo wp_kses_post(nl2br($message)); ?>
        </div>
        
        <?php if ($show_countdown && $reopen_timestamp) : ?>
            <div class="countdown-wrapper">
                <div class="countdown-title">Réouverture dans</div>
                <div class="countdown" id="countdown">
                    <div class="countdown-item">
                        <span class="countdown-value" id="days">0</span>
                        <span class="countdown-label">Jours</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="hours">0</span>
                        <span class="countdown-label">Heures</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="minutes">0</span>
                        <span class="countdown-label">Minutes</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="seconds">0</span>
                        <span class="countdown-label">Secondes</span>
                    </div>
                </div>
            </div>
            
            <script>
                const countdownDate = new Date(<?php echo $reopen_timestamp * 1000; ?>).getTime();
                
                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = countdownDate - now;
                    
                    if (distance < 0) {
                        location.reload();
                        return;
                    }
                    
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    document.getElementById('days').textContent = days;
                    document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                    document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                    document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
                }
                
                updateCountdown();
                setInterval(updateCountdown, 1000);
            </script>
        <?php endif; ?>
        
        <?php if (!empty($contact_email)) : ?>
            <div class="contact-info">
                <p>Pour toute question urgente, contactez-nous :</p>
                <p><a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($settings['show_social']) && $settings['show_social']) : ?>
            <div class="social-links">
                <?php if (!empty($settings['facebook_url'])) : ?>
                    <a href="<?php echo esc_url($settings['facebook_url']); ?>" target="_blank" rel="noopener">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z"/>
                        </svg>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($settings['twitter_url'])) : ?>
                    <a href="<?php echo esc_url($settings['twitter_url']); ?>" target="_blank" rel="noopener">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19.633 7.997c.013.175.013.349.013.523 0 5.325-4.053 11.461-11.46 11.461-2.282 0-4.402-.661-6.186-1.809.324.037.636.05.973.05a8.07 8.07 0 0 0 5.001-1.721 4.036 4.036 0 0 1-3.767-2.793c.249.037.499.062.761.062.361 0 .724-.05 1.061-.137a4.027 4.027 0 0 1-3.23-3.953v-.05c.537.299 1.16.486 1.82.511a4.022 4.022 0 0 1-1.796-3.354c0-.748.199-1.434.548-2.032a11.457 11.457 0 0 0 8.306 4.215c-.062-.3-.1-.611-.1-.923a4.026 4.026 0 0 1 4.028-4.028c1.16 0 2.207.486 2.943 1.272a7.957 7.957 0 0 0 2.556-.973 4.02 4.02 0 0 1-1.771 2.22 8.073 8.073 0 0 0 2.319-.624 8.645 8.645 0 0 1-2.019 2.083z"/>
                        </svg>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($settings['instagram_url'])) : ?>
                    <a href="<?php echo esc_url($settings['instagram_url']); ?>" target="_blank" rel="noopener">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M11.999 7.377a4.623 4.623 0 1 0 0 9.248 4.623 4.623 0 0 0 0-9.248zm0 7.627a3.004 3.004 0 1 1 0-6.008 3.004 3.004 0 0 1 0 6.008z"/>
                            <circle cx="16.806" cy="7.207" r="1.078"/>
                            <path d="M20.533 6.111A4.605 4.605 0 0 0 17.9 3.479a6.606 6.606 0 0 0-2.186-.42c-.963-.042-1.268-.054-3.71-.054s-2.755 0-3.71.054a6.554 6.554 0 0 0-2.184.42 4.6 4.6 0 0 0-2.633 2.632 6.585 6.585 0 0 0-.419 2.186c-.043.962-.056 1.267-.056 3.71 0 2.442 0 2.753.056 3.71.015.748.156 1.486.419 2.187a4.61 4.61 0 0 0 2.634 2.632 6.584 6.584 0 0 0 2.185.45c.963.042 1.268.055 3.71.055s2.755 0 3.71-.055a6.615 6.615 0 0 0 2.186-.419 4.613 4.613 0 0 0 2.633-2.633c.263-.7.404-1.438.419-2.186.043-.962.056-1.267.056-3.71s0-2.753-.056-3.71a6.581 6.581 0 0 0-.421-2.217zm-1.218 9.532a5.043 5.043 0 0 1-.311 1.688 2.987 2.987 0 0 1-1.712 1.711 4.985 4.985 0 0 1-1.67.311c-.95.044-1.218.055-3.654.055-2.438 0-2.687 0-3.655-.055a4.96 4.96 0 0 1-1.669-.311 2.985 2.985 0 0 1-1.719-1.711 5.08 5.08 0 0 1-.311-1.669c-.043-.95-.053-1.218-.053-3.654 0-2.437 0-2.686.053-3.655a5.038 5.038 0 0 1 .311-1.687c.305-.789.93-1.41 1.719-1.712a5.01 5.01 0 0 1 1.669-.311c.951-.043 1.218-.055 3.655-.055s2.687 0 3.654.055a4.96 4.96 0 0 1 1.67.311 2.991 2.991 0 0 1 1.712 1.712 5.08 5.08 0 0 1 .311 1.669c.043.951.054 1.218.054 3.655 0 2.436 0 2.698-.043 3.654h-.011z"/>
                        </svg>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($settings['linkedin_url'])) : ?>
                    <a href="<?php echo esc_url($settings['linkedin_url']); ?>" target="_blank" rel="noopener">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
