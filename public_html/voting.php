<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="images/awards2025/01.png" type="image/x-icon">
    <meta name="description" content="MRA Awards 2025 - Санал өгөх">
    <title>Санал өгөх - MRA Awards 2025</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary-color: #0a1c44;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
            background: #000000;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }
        
        /* Background Shapes */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.05;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            background: white;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 200px;
            height: 200px;
            background: white;
            top: 60%;
            right: 15%;
            animation-delay: 5s;
        }
        
        .shape-3 {
            width: 150px;
            height: 150px;
            background: white;
            bottom: 20%;
            left: 20%;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        /* Category Navigation */
        .category-nav {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 120;
            display: flex;
            gap: 0.5rem;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-width: 90%;
            overflow-x: auto;
            scrollbar-width: thin;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .category-nav.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .category-nav::-webkit-scrollbar {
            height: 4px;
        }
        
        .category-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .category-nav-item {
            padding: 0.5rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            white-space: nowrap;
            position: relative;
        }
        
        .category-nav-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .category-nav-item.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .category-nav-item.voted::after {
            content: '✓';
            position: absolute;
            top: -4px;
            right: -4px;
            background: #10b981;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }
        
        @media (max-width: 768px) {
            .category-nav {
                top: 10px;
                padding: 0.5rem 0.75rem;
                gap: 0.375rem;
            }
            
            .category-nav-item {
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }
        }
        
        /* Cinematic Container */
        .cinematic-container {
            overflow-y: scroll;
            height: 100vh;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 768px) {
            .cinematic-container {
                overflow-y: auto;
                height: 100vh;
                padding-bottom: 6rem; /* Extra space at bottom for last card */
            }
        }
        
        .cinematic-section {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 8rem 2rem 4rem;
            opacity: 0;
            transform: translateY(50px);
            transition: all 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: visible;
            flex-shrink: 0;
        }
        
        @media (max-width: 768px) {
            .cinematic-section {
                min-height: auto !important;
                height: auto !important;
                align-items: flex-start !important;
                padding-bottom: 3rem !important;
                opacity: 1 !important;
                transform: none !important;
            }
            
            /* Add extra padding to last section on mobile */
            .cinematic-section:last-child {
                padding-bottom: 8rem !important;
            }
            
            .cinematic-section.active {
                opacity: 1 !important;
                transform: none !important;
            }
        }
        
        .cinematic-section.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Allow sections with many cards to expand on desktop */
        .cinematic-section.category-section.has-many-cards {
            min-height: auto !important;
            height: auto !important;
            align-items: flex-start !important;
            padding-bottom: 4rem;
            flex-shrink: 0;
            opacity: 1 !important;
            transform: none !important;
        }
        
        .cinematic-section.has-many-cards {
            min-height: auto !important;
            height: auto !important;
            align-items: flex-start !important;
            flex-shrink: 0;
            opacity: 1 !important;
            transform: none !important;
        }
        
        .cinematic-section.has-many-cards.active {
            opacity: 1 !important;
            transform: none !important;
        }
        
        .cinematic-content {
            max-width: 1400px;
            width: 100%;
            text-align: center;
            z-index: 2;
            padding-top: 0;
            min-height: auto;
            flex-shrink: 0;
        }
        
        /* Ensure content can expand for sections with many cards */
        .cinematic-section.has-many-cards .cinematic-content {
            min-height: auto !important;
            height: auto !important;
            flex-shrink: 0;
        }
        
        /* Ensure participants grid can expand */
        .cinematic-section.has-many-cards .participants-grid {
            min-height: auto;
            height: auto;
        }
        
        /* Hero Section - Cinematic */
        .cinematic-hero {
            background: linear-gradient(135deg, #000000 0%, #000000 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero-video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            opacity: 0.3;
        }
        
        .cinematic-hero .cinematic-content {
            position: relative;
            z-index: 1;
        }
        
        .cinematic-hero .hero-image {
            max-width: 600px;
            width: 100%;
            margin: 0 auto 3rem;
        }
        
        .cinematic-hero h1 {
            font-size: clamp(2.5rem, 8vw, 5rem);
            margin-bottom: 2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.1;
            color: white;
        }
        
        .cinematic-hero p {
            font-size: clamp(1.1rem, 2.5vw, 1.5rem);
            max-width: 800px;
            margin: 0 auto;
            opacity: 0.9;
            line-height: 1.6;
            color: white;
        }
        
        /* Category Section */
        .category-section {
            background: linear-gradient(135deg, #000000 0%, #000000 100%);
            color: white;
        }
        
        .category-section h2 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            margin-bottom: 3rem;
            color: white;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }
        
        .category-section h2::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, transparent, #3b82f6, transparent);
            border-radius: 2px;
        }
        
        /* Compact Participants Grid - 5 columns desktop, 3 medium, 2 mobile */
        .participants-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
            margin-top: 3rem;
            width: 100%;
            overflow: visible;
        }
        
        @media (max-width: 1400px) {
            .participants-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        @media (max-width: 1200px) {
            .participants-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .participants-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .participants-grid {
                gap: 0.75rem;
            }
        }
        
        /* Enhanced Participant Card - Logo-Centric Design */
        .participant-card {
            background: #f9f9f9;
            backdrop-filter: blur(10px);
            border-radius: 1.25rem;
            padding: 1.5rem 1.25rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: scale(0.95) translateY(20px);
            animation: cardFadeIn 0.6s ease forwards;
            position: relative;
            overflow: hidden;
            border: none;
            min-height: 280px;
        }
        
        /* Background Image Layer */
        .participant-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('img/bg-image-mra.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 0;
            border-radius: 1.25rem;
        }
        
        /* Gradient Overlay Layer */
        .participant-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgb(9 9 9 / 6%) 45.3%, rgb(9, 9, 9) 59.5%);
            z-index: 1;
            border-radius: 1.25rem;
            pointer-events: none;
        }
        
        @media (max-width: 768px) {
            .participant-card {
                min-height: auto;
                height: auto;
                min-height: 115px;
                padding: 0.5rem;
                display: grid;
                grid-template-columns: 100px 1fr auto;
                grid-template-rows: auto auto;
                gap: 0.5rem 0.5rem;
                align-items: center;
                border-radius: 0.75rem;
                position: relative;
                overflow: hidden;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }
            
            /* Remove white edges by matching border-radius on pseudo-elements */
            .participant-card::before,
            .participant-card::after {
                border-radius: 0.75rem;
            }
            
            /* Logo in first column, spans both rows */
            .participant-card > .participant-logo-container {
                grid-column: 1;
                grid-row: 1 / 3;
                width: 100px;
                height: 100px;
                margin-bottom: 0;
            }
            
            /* Make logo image fill the container */
            .participant-card > .participant-logo-container .participant-logo {
                max-width: 100% !important;
                max-height: 100% !important;
                width: 100% !important;
                height: 100% !important;
                object-fit: contain;
            }
            
            /* Name in second column, first row */
            .participant-card > .participant-name {
                grid-column: 2;
                grid-row: 1;
                margin-bottom: 0;
            }
            
            /* Vote count in second column, second row */
            .participant-card > .vote-count {
                grid-column: 2;
                grid-row: 2;
                margin-bottom: 0;
            }
            
            /* Button in third column, spans both rows */
            .participant-card > .vote-btn {
                grid-column: 3;
                grid-row: 1 / 3;
                width: auto;
                min-width: 65px;
                padding: 0.5rem 0.5rem;
                font-size: 0.65rem;
                white-space: nowrap;
                align-self: center;
            }
            
            /* Hide checkmark on mobile */
            .participant-card > .vote-checkmark {
                display: none;
            }
        }
        
        /* Visual Variety - Alternate Card Styles */
        .participant-card:nth-child(4n+1) {
            border-color: rgba(16, 185, 129, 0.2);
        }
        
        .participant-card:nth-child(4n+2) {
            border-color: rgba(59, 130, 246, 0.2);
        }
        
        .participant-card:nth-child(4n+3) {
            border-color: rgba(168, 85, 247, 0.2);
        }
        
        .participant-card:nth-child(4n+4) {
            border-color: rgba(236, 72, 153, 0.2);
        }
        
        
        .participant-card.visible {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        
        @keyframes cardFadeIn {
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        /* Ensure cards are visible on mobile even before animation */
        @media (max-width: 768px) {
            .participant-card {
                opacity: 1 !important;
                transform: scale(1) translateY(0) !important;
                animation: none !important;
            }
            
            .participant-card.visible {
                opacity: 1 !important;
            }
            
            .participants-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 0.75rem !important;
                width: 100% !important;
                padding: 0 0.75rem;
                max-width: 100%;
            }
            
            .participant-card {
                max-width: 100%;
                box-sizing: border-box;
            }
        }
        
        .participant-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
        }
        
        .participant-card.voted {
            border-color: #10b981;
            border-width: 2px;
        }
        
        .participant-card.voted::after {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgb(9 9 9 / 60%) 100%),
                        linear-gradient(rgb(9 9 9 / 6%) 45.3%, rgb(9, 9, 9) 59.5%);
        }
        
        /* Checkmark for voted cards - using a separate approach */
        .participant-card.voted::before {
            content: '✓';
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: #10b981;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            animation: checkmarkPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 10;
        }
        
        @keyframes checkmarkPop {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        /* Logo-Centric Layout - 60% logo space */
        .participant-logo-container {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            border-radius: 1rem;
            padding: 1rem;
            position: relative;
            overflow: hidden;
            z-index: 2;
        }
        
        @media (max-width: 768px) {
            /* Logo container size is handled in the card grid layout above */
            .participant-logo {
                max-width: 100% !important;
                max-height: 100% !important;
                width: 100% !important;
                height: 100% !important;
                object-fit: contain;
            }
        }
        
        .participant-logo-container::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
        }
        
        .participant-logo {
            max-width: 90%;
            max-height: 90%;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 0.5rem;
            transition: transform 0.3s ease;
        }
        
        .participant-card:hover .participant-logo {
            transform: scale(1.05);
        }
        
        .participant-logo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* Name - 25% of card space */
        .participant-name {
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
            line-height: 1.3;
            min-height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }
        
        @media (max-width: 768px) {
            .participant-name {
                font-size: 0.875rem;
                margin-bottom: 0.25rem;
                min-height: auto;
                justify-content: center;
                text-align: left;
            }
        }
        
        /* Vote Count - Prominent */
        .vote-count {
            font-size: 0.875rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.75rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        
        @media (max-width: 768px) {
            .vote-count {
                font-size: 0.75rem;
                margin-bottom: 0;
            }
        }
        
        .vote-count-number {
            font-size: 1.1rem;
            font-weight: 800;
            color: #10b981;
            margin-left: 0.25rem;
        }
        
        /* Vote Button - 15% of card space */
        .vote-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.875rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.5), 0 0 20px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .vote-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .vote-btn:active::before {
            width: 300px;
            height: 300px;
        }
        
        .vote-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(59, 130, 246, 0.6), 0 0 30px rgba(59, 130, 246, 0.4);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .vote-btn:disabled {
            background: #374151;
            color: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            border-color: rgba(255, 255, 255, 0.1);
            opacity: 0.6;
        }
        
        .vote-btn.cooldown-disabled {
            background: #374151;
            color: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            border-color: rgba(255, 255, 255, 0.1);
            opacity: 0.6;
        }
        
        .vote-btn.voted {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.5), 0 0 20px rgba(16, 185, 129, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .vote-btn.undo-enabled {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 50%, #6d28d9 100%);
            box-shadow: 0 4px 16px rgba(139, 92, 246, 0.5), 0 0 20px rgba(139, 92, 246, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .vote-btn.undo-enabled:hover:not(:disabled) {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 50%, #5b21b6 100%);
            box-shadow: 0 6px 24px rgba(139, 92, 246, 0.6), 0 0 30px rgba(139, 92, 246, 0.4);
        }
        
        .vote-btn.loading {
            position: relative;
            color: transparent;
        }
        
        .vote-btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        /* Navigation Dots */
        .cinematic-nav {
            position: fixed;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 150;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .cinematic-nav-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cinematic-nav-dot.active {
            background: white;
            transform: scale(1.3);
        }
        
        @media (max-width: 768px) {
            .cinematic-nav {
                display: none;
            }
        }
        
        /* Scroll Indicator */
        .scroll-indicator {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 150;
            color: white;
            font-size: 2rem;
            animation: bounce 2s infinite;
            opacity: 0.7;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-10px); }
        }
        
        .scroll-indicator.hidden {
            display: none;
        }
        
        /* Success Message */
        .success-message {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            padding: 1.25rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
            z-index: 200;
            opacity: 0;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .success-message::before {
            content: '✓';
            font-size: 1.5rem;
        }
        
        .success-message.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        /* Error Message */
        .error-message {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            color: white;
            padding: 1.25rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
            z-index: 200;
            opacity: 0;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
        }
        
        .error-message.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        /* Header */
        @media (max-width: 768px) {
            .cinematic-section {
                padding: 5rem 1rem 2rem;
                align-items: flex-start;
                min-height: auto !important;
                height: auto !important;
                flex-shrink: 0;
            }
            
            /* Add extra padding to last section on mobile to ensure last card is fully visible */
            .cinematic-section:last-child {
                padding-bottom: 8rem !important;
            }
            
            .cinematic-content {
                width: 100%;
                min-height: auto;
                flex-shrink: 0;
            }
            
            .participants-grid {
                margin-top: 2rem;
                width: 100%;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
                margin-bottom: 2rem; /* Extra margin at bottom of grid */
            }
        }
        
        /* Number Animation */
        @keyframes numberPop {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        
        .vote-count-number.animating {
            animation: numberPop 0.4s ease;
        }
    </style>
</head>
<body>
    <!-- Background Shapes -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    
    <!-- Category Navigation -->
    <div class="category-nav" id="categoryNav"></div>
    
    <div class="cinematic-container" id="cinematicContainer" style="padding-top: 0;">
        <div id="loadingMessage" class="cinematic-section category-section active">
            <div class="cinematic-content">
                <h2>Уншиж байна...</h2>
            </div>
        </div>
    </div>

    <div class="scroll-indicator" id="scrollIndicator">↓</div>
    
    <!-- Success/Error Messages -->
    <div class="success-message" id="successMessage">Санал амжилттай бүртгэгдлээ!</div>
    <div class="error-message" id="errorMessage"></div>

    <script>
        let categories = [];
        let participantsByCategory = {};
        let votedCategories = new Set();
        let totalCategories = 0;
        let existingVotes = {}; // categoryId => participantId
        let voteCooldowns = {}; // categoryId => {canVoteAgainAt: timestamp, timeRemaining: seconds}
        
        // Load categories and participants
        async function loadData() {
            try {
                const categoriesResponse = await fetch('api/get-categories.php');
                const categoriesData = await categoriesResponse.json();
                
                if (!categoriesData.success) {
                    throw new Error('Failed to load categories');
                }
                
                categories = categoriesData.categories;
                totalCategories = categories.length;
                
                for (const category of categories) {
                    const participantsResponse = await fetch(`api/get-participants.php?category_id=${category.id}`);
                    const participantsData = await participantsResponse.json();
                    
                    if (participantsData.success) {
                        participantsByCategory[category.id] = participantsData.participants;
                    }
                }
                
                // Load existing vote status
                const voteStatusResponse = await fetch('api/get-vote-status.php', {
                    credentials: 'include'
                });
                const voteStatusData = await voteStatusResponse.json();
                
                if (voteStatusData.success && voteStatusData.votes) {
                    const currentTime = Date.now();
                    // Mark categories as voted (only if still within 2-hour cooldown)
                    Object.keys(voteStatusData.votes).forEach(categoryId => {
                        const voteInfo = voteStatusData.votes[categoryId];
                        const canVoteAgainAt = new Date(voteInfo.can_vote_again_at).getTime();
                        
                        // Only mark as voted if still in cooldown period
                        if (currentTime < canVoteAgainAt) {
                            votedCategories.add(parseInt(categoryId));
                            // Store which participant was voted for in each category
                            existingVotes[categoryId] = voteInfo.participant_id;
                            // Store cooldown information
                            voteCooldowns[categoryId] = {
                                canVoteAgainAt: canVoteAgainAt,
                                timeRemaining: voteInfo.time_remaining
                            };
                        }
                    });
                }
                
                // Start periodic check to re-enable voting after cooldown expires
                startCooldownChecker();
                
                renderVotingPage();
            } catch (error) {
                console.error('Error loading data:', error);
                document.getElementById('loadingMessage').innerHTML = 
                    '<div class="cinematic-content"><h2 style="color: #ef4444;">Алдаа гарлаа. Дахин оролдоно уу.</h2></div>';
            }
        }
        
        function renderVotingPage() {
            const container = document.getElementById('cinematicContainer');
            container.innerHTML = '';
            
            // Add Hero Section
            const heroSection = document.createElement('section');
            heroSection.className = 'cinematic-section cinematic-hero';
            heroSection.id = 'section-hero';
            heroSection.innerHTML = `
                <video 
                    class="hero-video-background" 
                    autoplay 
                    loop 
                    muted 
                    playsinline
                    webkit-playsinline
                    preload="auto"
                >
                    <source src="img/hero-ribbon-69d8be316b3dd5adc766c1d5e20380cc.webm" type="video/webm">
                </video>
                <div class="cinematic-content">
                    <img
                        src="award2025/08.png"
                        alt="MRA Awards 2025"
                        class="hero-image"
                    />
                    <h1>MRA Awards 2025</h1>
                    <p>
                        Та Монголын хоол үйлдвэрлэл, уух зүйлсийн үйлчилгээний салбарын шилдэгүүдээ тодруулах шалгаруулалтын олон нийтийн санал асуулганд 8 номинаци тус бүрт нь 1 санал өгч оролцоно уу. Баярлалаа.
                    </p>
                </div>
            `;
            container.appendChild(heroSection);
            
            // Ensure video plays on mobile devices
            setTimeout(() => {
                const video = heroSection.querySelector('.hero-video-background');
                if (video) {
                    // Ensure all mobile-friendly attributes
                    video.muted = true;
                    video.setAttribute('playsinline', '');
                    video.setAttribute('webkit-playsinline', '');
                    video.setAttribute('preload', 'auto');
                    
                    // Function to attempt playing the video
                    const attemptPlay = () => {
                        const playPromise = video.play();
                        if (playPromise !== undefined) {
                            playPromise.catch(error => {
                                // Autoplay failed, will try on user interaction
                                console.log('Video autoplay prevented, will play on interaction');
                            });
                        }
                    };
                    
                    // Try to play when video is loaded
                    if (video.readyState >= 2) {
                        // Video is already loaded
                        attemptPlay();
                    } else {
                        // Wait for video to load
                        video.addEventListener('loadeddata', attemptPlay, { once: true });
                        video.addEventListener('canplay', attemptPlay, { once: true });
                    }
                    
                    // Fallback: Play on first user interaction if autoplay fails
                    const playOnInteraction = () => {
                        video.play().catch(() => {});
                        document.removeEventListener('touchstart', playOnInteraction);
                        document.removeEventListener('click', playOnInteraction);
                        document.removeEventListener('scroll', playOnInteraction);
                    };
                    document.addEventListener('touchstart', playOnInteraction, { once: true, passive: true });
                    document.addEventListener('click', playOnInteraction, { once: true });
                    document.addEventListener('scroll', playOnInteraction, { once: true, passive: true });
                }
            }, 200);
            
            categories.forEach((category, index) => {
                const section = document.createElement('section');
                section.className = 'cinematic-section category-section';
                section.id = `section-${index + 1}`;
                section.dataset.categoryId = category.id;
                section.dataset.categoryIndex = index;
                
                const participants = participantsByCategory[category.id] || [];
                
                section.innerHTML = `
                    <div class="cinematic-content">
                        <h2>${escapeHtml(category.name)}</h2>
                        ${participants.length === 0 
                            ? '<p style="opacity: 0.8; font-size: 1.2rem;">Энэ ангилалд оролцогч байхгүй байна.</p>'
                            : `<div class="participants-grid" id="participants-${category.id}"></div>`
                        }
                    </div>
                `;
                
                container.appendChild(section);
                
                // Add has-many-cards class if there are 8 or more participants
                if (participants.length >= 8) {
                    section.classList.add('has-many-cards');
                    // Force section to be visible and expanded immediately
                    section.style.minHeight = 'auto';
                    section.style.height = 'auto';
                    section.style.opacity = '1';
                    section.style.transform = 'none';
                    section.classList.add('active');
                }
                
                if (participants.length > 0) {
                    renderParticipants(category.id, participants);
                }
            });
            
            // Immediately activate hero section
            const heroSectionEl = document.getElementById('section-hero');
            if (heroSectionEl) {
                heroSectionEl.classList.add('active');
            }
            
            // Immediately activate first category section
            const firstSection = document.getElementById('section-1');
            if (firstSection) {
                firstSection.classList.add('active');
            }
            
            // On mobile, activate all sections immediately to ensure visibility
            const isMobile = window.innerWidth <= 768;
            if (isMobile) {
                document.querySelectorAll('.cinematic-section').forEach(section => {
                    section.classList.add('active');
                });
            }
            
            createCategoryNavigation();
            initializeScroll();
            createNavigation();
        }
        
        function createCategoryNavigation() {
            const nav = document.getElementById('categoryNav');
            nav.innerHTML = '';
            
            categories.forEach((category, index) => {
                const item = document.createElement('div');
                item.className = 'category-nav-item';
                if (index === 0) item.classList.add('active');
                item.textContent = category.name;
                item.dataset.categoryIndex = index;
                
                item.addEventListener('click', () => {
                    const section = document.getElementById(`section-${index + 1}`);
                    if (section) {
                        section.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    // Scroll nav item into view on mobile
                    scrollNavItemIntoView(item);
                });
                
                nav.appendChild(item);
            });
        }
        
        function scrollNavItemIntoView(item) {
            if (!item) return;
            
            // Only auto-scroll on smaller devices where nav might overflow
            if (window.innerWidth <= 768) {
                item.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }
        }
        
        function updateCategoryNav() {
            const sections = document.querySelectorAll('.cinematic-section');
            const navItems = document.querySelectorAll('.category-nav-item');
            const categoryNav = document.getElementById('categoryNav');
            const heroSection = document.getElementById('section-hero');
            
            // Check if user has scrolled past hero section
            if (heroSection) {
                const heroRect = heroSection.getBoundingClientRect();
                const heroBottom = heroRect.bottom;
                const viewportTop = window.innerHeight * 0.3; // Show nav when hero is 30% past top
                
                if (heroBottom < viewportTop) {
                    categoryNav.classList.add('visible');
                } else {
                    categoryNav.classList.remove('visible');
                }
            }
            
            let currentIndex = -1;
            let minDistance = Infinity;
            
            // Skip hero section (index 0) and only check category sections
            sections.forEach((section, index) => {
                // Skip hero section
                if (section.id === 'section-hero') return;
                
                const rect = section.getBoundingClientRect();
                const sectionCenter = rect.top + rect.height / 2;
                const viewportCenter = window.innerHeight / 2;
                const distance = Math.abs(sectionCenter - viewportCenter);
                
                if (rect.top < viewportCenter && rect.bottom > viewportCenter) {
                    if (distance < minDistance) {
                        minDistance = distance;
                        // Map section index to category index (section-1 = category 0, section-2 = category 1, etc.)
                        currentIndex = index - 1;
                    }
                }
            });
            
            navItems.forEach((item, index) => {
                const wasActive = item.classList.contains('active');
                const isActive = index === currentIndex;
                item.classList.toggle('active', isActive);
                
                // Get the corresponding category section
                const categorySection = document.getElementById(`section-${index + 1}`);
                const categoryId = parseInt(categorySection?.dataset.categoryId || 0);
                if (votedCategories.has(categoryId)) {
                    item.classList.add('voted');
                } else {
                    item.classList.remove('voted');
                }
                
                // Scroll active item into view if it just became active
                if (isActive && !wasActive) {
                    scrollNavItemIntoView(item);
                }
            });
        }
        
        function renderParticipants(categoryId, participants) {
            const container = document.getElementById(`participants-${categoryId}`);
            if (!container) return;
            
            const hasVotedInCategory = votedCategories.has(categoryId);
            const votedParticipantId = existingVotes[categoryId];
            
            container.innerHTML = participants.map((participant, index) => {
                const logoUrl = participant.logo_path 
                    ? escapeHtml(participant.logo_path) 
                    : '';
                
                const isVotedParticipant = hasVotedInCategory && votedParticipantId === participant.id;
                const isOtherParticipant = hasVotedInCategory && votedParticipantId !== participant.id;
                
                return `
                    <div class="participant-card ${isVotedParticipant ? 'voted' : ''}" style="animation-delay: ${index * 0.05}s" data-participant-id="${participant.id}">
                        <div class="vote-checkmark">✓</div>
                        <div class="participant-logo-container">
                            ${participant.logo_path 
                                ? `<img src="${logoUrl}" alt="${escapeHtml(participant.name)}" class="participant-logo">`
                                : `<div class="participant-logo-placeholder">No Logo</div>`
                            }
                        </div>
                        <div class="participant-name">${escapeHtml(participant.name)}</div>
                        <div class="vote-count">
                            Санал: <span class="vote-count-number" id="vote-count-${participant.id}">${participant.vote_count}</span>
                        </div>
                        <button 
                            class="vote-btn ${isVotedParticipant ? 'voted undo-enabled' : ''} ${isOtherParticipant ? 'cooldown-disabled' : ''}" 
                            onclick="${isVotedParticipant ? `undoVote(${categoryId}, ${participant.id})` : `vote(${participant.id}, ${categoryId})`}"
                            id="vote-btn-${participant.id}"
                        >
                            ${isVotedParticipant ? 'Болих' : 'Санал өгөх'}
                        </button>
                    </div>
                `;
            }).join('');
            
            // Make cards visible immediately
            // On mobile, show all cards immediately without delay
            const isMobile = window.innerWidth <= 768;
            if (isMobile) {
                container.querySelectorAll('.participant-card').forEach((card) => {
                    card.classList.add('visible');
                });
            } else {
                setTimeout(() => {
                    container.querySelectorAll('.participant-card').forEach((card, idx) => {
                        setTimeout(() => {
                            card.classList.add('visible');
                        }, idx * 30);
                    });
                }, 200);
            }
        }
        
        async function vote(participantId, categoryId) {
            // Note: We no longer block voting here - the API will check if 2 hours have passed
            // This allows users to vote again after the cooldown period
            
            const btn = document.getElementById(`vote-btn-${participantId}`);
            if (!btn) return;
            
            // Check if user has already voted in this category (cooldown check)
            if (votedCategories.has(categoryId) && voteCooldowns[categoryId]) {
                const cooldown = voteCooldowns[categoryId];
                const currentTime = Date.now();
                
                if (currentTime < cooldown.canVoteAgainAt) {
                    // Still in cooldown - show error message
                    const timeRemaining = cooldown.canVoteAgainAt - currentTime;
                    const hours = Math.floor(timeRemaining / (60 * 60 * 1000));
                    const minutes = Math.floor((timeRemaining % (60 * 60 * 1000)) / (60 * 1000));
                    
                    let message = 'Та энэ ангилалд санал өгсөн байна. ';
                    if (hours > 0) {
                        message += hours + ' цаг ';
                    }
                    if (minutes > 0) {
                        message += minutes + ' минут ';
                    }
                    message += 'дараа дахин санал өгөх боломжтой.';
                    
                    showError(message);
                    return;
                } else {
                    // Cooldown expired - allow voting
                    votedCategories.delete(categoryId);
                    delete existingVotes[categoryId];
                    delete voteCooldowns[categoryId];
                }
            }
            
            // Check if button is in cooldown-disabled state
            if (btn.classList.contains('cooldown-disabled')) {
                // Button is visually disabled due to cooldown - check and show error
                if (votedCategories.has(categoryId) && voteCooldowns[categoryId]) {
                    const cooldown = voteCooldowns[categoryId];
                    const currentTime = Date.now();
                    
                    if (currentTime < cooldown.canVoteAgainAt) {
                        // Still in cooldown - error already shown above, just return
                        return;
                    }
                }
                // Cooldown expired - remove disabled state
                btn.classList.remove('cooldown-disabled');
            }
            
            // If button is actually disabled (not just cooldown), don't proceed
            if (btn.disabled) return;
            
            const card = document.querySelector(`[data-participant-id="${participantId}"]`);
            
            btn.disabled = true;
            btn.classList.add('loading');
            
            try {
                const response = await fetch('api/vote.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include', // Include cookies (session) with request
                    body: JSON.stringify({
                        participant_id: participantId
                    })
                });
                
                const result = await response.json();
                
                // Check if response was successful (status 200-299) or if it's an error response
                if (!response.ok) {
                    // Handle error response (like 403 for cooldown)
                    btn.disabled = false;
                    btn.classList.remove('loading');
                    const errorMsg = result.error || result.message || 'Санал өгөхөд алдаа гарлаа.';
                    console.log('Vote error:', errorMsg, result);
                    showError(errorMsg);
                    return;
                }
                
                if (result.success) {
                    // Animate vote count update
                    const voteCountEl = document.getElementById(`vote-count-${participantId}`);
                    if (voteCountEl) {
                        voteCountEl.classList.add('animating');
                        setTimeout(() => {
                            voteCountEl.textContent = result.vote_count;
                            setTimeout(() => {
                                voteCountEl.classList.remove('animating');
                            }, 400);
                        }, 200);
                    }
                    
                    // Mark card as voted
                    if (card) {
                        card.classList.add('voted');
                    }
                    
                    // Update button to show undo option
                    btn.classList.remove('loading');
                    btn.classList.add('voted');
                    btn.classList.add('undo-enabled');
                    btn.textContent = 'Болих';
                    btn.disabled = false;
                    btn.onclick = () => undoVote(categoryId, participantId);
                    
                    votedCategories.add(categoryId);
                    existingVotes[categoryId] = participantId;
                    
                    // Store cooldown information (2 hours from now)
                    const twoHoursInMs = 2 * 60 * 60 * 1000;
                    voteCooldowns[categoryId] = {
                        canVoteAgainAt: Date.now() + twoHoursInMs,
                        timeRemaining: twoHoursInMs
                    };
                    
                    // Mark other buttons as disabled (but allow clicks to show cooldown error)
                    const categorySection = document.querySelector(`[data-category-id="${categoryId}"]`);
                    if (categorySection) {
                        categorySection.querySelectorAll('.vote-btn').forEach(b => {
                            if (b.id !== `vote-btn-${participantId}`) {
                                // Don't actually disable - use CSS class instead so clicks still work
                                // This allows the error message to show when user tries to vote again
                                b.classList.add('cooldown-disabled');
                                b.style.pointerEvents = 'auto'; // Ensure clicks still work
                            }
                        });
                    }
                    
                    updateCategoryNav();
                    showSuccess('Санал амжилттай бүртгэгдлээ!');
                } else {
                    btn.disabled = false;
                    btn.classList.remove('loading');
                    showError(result.error || 'Санал өгөхөд алдаа гарлаа.');
                }
            } catch (error) {
                btn.disabled = false;
                btn.classList.remove('loading');
                showError('Алдаа гарлаа. Дахин оролдоно уу.');
            }
        }
        
        function showSuccess(message) {
            const msg = document.getElementById('successMessage');
            // Always set the message - use default if none provided
            msg.textContent = message || 'Санал амжилттай бүртгэгдлээ!';
            msg.classList.add('show');
            setTimeout(() => {
                msg.classList.remove('show');
            }, 3000);
        }
        
        function showError(message) {
            const msg = document.getElementById('errorMessage');
            if (!msg) {
                console.error('Error message element not found');
                alert(message); // Fallback to alert if element not found
                return;
            }
            msg.textContent = message;
            msg.classList.add('show');
            console.log('Showing error message:', message);
            setTimeout(() => {
                msg.classList.remove('show');
            }, 6000); // Increased to 6 seconds for longer messages
        }
        
        async function undoVote(categoryId, participantId) {
            const btn = document.getElementById(`vote-btn-${participantId}`);
            if (!btn) return;
            
            const card = document.querySelector(`[data-participant-id="${participantId}"]`);
            
            btn.disabled = true;
            btn.classList.add('loading');
            
            try {
                const response = await fetch('api/undo-vote.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        category_id: categoryId
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Animate vote count update
                    const voteCountEl = document.getElementById(`vote-count-${result.participant_id}`);
                    if (voteCountEl) {
                        voteCountEl.classList.add('animating');
                        setTimeout(() => {
                            voteCountEl.textContent = result.vote_count;
                            setTimeout(() => {
                                voteCountEl.classList.remove('animating');
                            }, 400);
                        }, 200);
                    }
                    
                    // Remove voted state from card
                    if (card) {
                        card.classList.remove('voted');
                    }
                    
                    // Reset button to original state
                    btn.classList.remove('loading');
                    btn.classList.remove('voted');
                    btn.classList.remove('undo-enabled');
                    btn.textContent = 'Санал өгөх';
                    btn.disabled = false;
                    btn.onclick = () => vote(result.participant_id, categoryId);
                    
                    // Remove from voted categories, existing votes, and cooldowns
                    votedCategories.delete(categoryId);
                    delete existingVotes[categoryId];
                    delete voteCooldowns[categoryId];
                    
                    // Re-enable all vote buttons in this category
                    const categorySection = document.querySelector(`[data-category-id="${categoryId}"]`);
                    if (categorySection) {
                        categorySection.querySelectorAll('.vote-btn').forEach(b => {
                            b.disabled = false;
                            b.classList.remove('cooldown-disabled');
                            // Reset any other buttons that might have been in voted state
                            if (!b.classList.contains('undo-enabled')) {
                                b.classList.remove('voted');
                                const btnParticipantId = b.id.replace('vote-btn-', '');
                                b.onclick = () => vote(parseInt(btnParticipantId), categoryId);
                            }
                        });
                    }
                    
                    // Remove voted state from other cards in this category
                    if (categorySection) {
                        categorySection.querySelectorAll('.participant-card').forEach(c => {
                            c.classList.remove('voted');
                        });
                    }
                    
                    updateCategoryNav();
                    showSuccess('Санал цуцлагдлаа. Дахин санал өгөх боломжтой.');
                } else {
                    btn.disabled = false;
                    btn.classList.remove('loading');
                    showError(result.error || 'Санал цуцлах алдаа гарлаа.');
                }
            } catch (error) {
                btn.disabled = false;
                btn.classList.remove('loading');
                showError('Алдаа гарлаа. Дахин оролдоно уу.');
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function initializeScroll() {
            const sections = document.querySelectorAll('.cinematic-section');
            if (sections.length > 0) {
                sections[0].classList.add('active');
            }
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting && entry.intersectionRatio > 0.3) {
                        entry.target.classList.add('active');
                        updateNavigation();
                        updateCategoryNav();
                    }
                });
            }, {
                threshold: [0.1, 0.3, 0.5, 0.7],
                rootMargin: '-20% 0px -20% 0px'
            });
            
            sections.forEach((section) => {
                observer.observe(section);
            });
            
            // Update on scroll
            const container = document.getElementById('cinematicContainer');
            container.addEventListener('scroll', () => {
                updateNavigation();
                updateCategoryNav();
            });
        }
        
        function createNavigation() {
            const existingNav = document.querySelector('.cinematic-nav');
            if (existingNav) existingNav.remove();
            
            const nav = document.createElement('div');
            nav.className = 'cinematic-nav';
            
            categories.forEach((category, index) => {
                const dot = document.createElement('div');
                dot.className = 'cinematic-nav-dot';
                if (index === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    const section = document.getElementById(`section-${index + 1}`);
                    if (section) {
                        section.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
                nav.appendChild(dot);
            });
            
            document.body.appendChild(nav);
        }
        
        function updateNavigation() {
            const sections = document.querySelectorAll('.cinematic-section');
            const dots = document.querySelectorAll('.cinematic-nav-dot');
            
            let currentIndex = -1;
            let minDistance = Infinity;
            
            sections.forEach((section, index) => {
                // Skip hero section
                if (section.id === 'section-hero') return;
                
                const rect = section.getBoundingClientRect();
                const sectionCenter = rect.top + rect.height / 2;
                const viewportCenter = window.innerHeight / 2;
                const distance = Math.abs(sectionCenter - viewportCenter);
                
                if (rect.top < viewportCenter && rect.bottom > viewportCenter) {
                    if (distance < minDistance) {
                        minDistance = distance;
                        // Map to category index (section-1 = category 0, section-2 = category 1, etc.)
                        currentIndex = index - 1;
                    }
                }
            });
            
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
            
            const scrollIndicator = document.getElementById('scrollIndicator');
            const lastCategoryIndex = categories.length - 1;
            if (currentIndex === lastCategoryIndex) {
                scrollIndicator.classList.add('hidden');
            } else {
                scrollIndicator.classList.remove('hidden');
            }
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                const sections = Array.from(document.querySelectorAll('.cinematic-section'));
                const currentSection = sections.find(s => {
                    const rect = s.getBoundingClientRect();
                    return rect.top < window.innerHeight / 2 && rect.bottom > window.innerHeight / 2;
                });
                
                if (currentSection) {
                    const currentIndex = sections.indexOf(currentSection);
                    const nextIndex = e.key === 'ArrowDown' 
                        ? Math.min(currentIndex + 1, sections.length - 1)
                        : Math.max(currentIndex - 1, 0);
                    
                    sections[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        function startCooldownChecker() {
            setInterval(() => {
                const currentTime = Date.now();
                
                Object.keys(voteCooldowns).forEach(categoryId => {
                    const cooldown = voteCooldowns[categoryId];
                    
                    if (currentTime >= cooldown.canVoteAgainAt) {
                        // Cooldown expired - re-enable voting for this category
                        votedCategories.delete(parseInt(categoryId));
                        delete existingVotes[categoryId];
                        delete voteCooldowns[categoryId];
                        
                        // Re-enable all vote buttons in this category
                        const categorySection = document.querySelector(`[data-category-id="${categoryId}"]`);
                        if (categorySection) {
                            categorySection.querySelectorAll('.vote-btn').forEach(b => {
                                b.disabled = false;
                                b.classList.remove('voted', 'undo-enabled', 'cooldown-disabled');
                                const btnParticipantId = b.id.replace('vote-btn-', '');
                                b.textContent = 'Санал өгөх';
                                b.onclick = () => vote(parseInt(btnParticipantId), parseInt(categoryId));
                            });
                            
                            // Remove voted state from cards
                            categorySection.querySelectorAll('.participant-card').forEach(c => {
                                c.classList.remove('voted');
                            });
                        }
                        
                        updateCategoryNav();
                    }
                });
            }, 60000); // Check every minute
        }
        
        // Load data on page load
        loadData();
    </script>
</body>
</html>
