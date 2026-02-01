<style>
    :root {
        --primary: #8b5cf6;
        --primary-dark: #7c3aed;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --bg-light: #f1f5f9;
        --bg-dark: #0f172a;
        --card-light: #ffffff;
        --card-dark: #121826;
        --text-light: #334155;
        --text-dark: #f1f5f9;
        --border-light: #e2e8f0;
        --border-dark: #1e293b;
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    body.dark-mode {
        --bg-dark: #0b0f1a;
        --card-dark: #121826;
        --border-dark: #1e293b;
        --glass-bg: rgba(30, 41, 59, 0.7);
        --glass-border: rgba(255, 255, 255, 0.1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body {
        font-family: 'Tajawal', sans-serif;
        direction: rtl;
        text-align: right;
        background: var(--bg-light);
        color: var(--text-light);
        transition: background-color 0.3s, color 0.3s;
        min-height: 100vh;
    }

    body.dark-mode {
        background: var(--bg-dark);
        color: var(--text-dark);
    }

    .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
    
    /* Buttons */
    .btn {
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.5);
    }

    /* Cards */
    .card {
        background: var(--card-light);
        border-radius: 20px;
        padding: 24px;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }

    body.dark-mode .card {
        background: var(--card-dark);
        border-color: var(--border-dark);
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--border-light);
        border-radius: 10px;
    }

    body.dark-mode ::webkit-scrollbar-thumb {
        background: var(--border-dark);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
