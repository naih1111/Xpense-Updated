<x-guest-layout>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        .getstarted-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #0A1B3F;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 0;
        }

        .cards-container {
            display: flex;
            justify-content: center;
            gap: 90px;
            margin: 60px 0;
            flex-wrap: wrap;
            max-width: 1200px;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 24px;
            padding: 20px;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .card h2 {
            color: #0A1B3F;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .card p {
            color: #64748B;
            line-height: 1.6;
        }

        .getstarted-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgb(79, 139, 229);
            color: white;
            text-decoration: none;
            padding: 16px 30px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.3s ease;
            margin-top: 40px;
        }

        .getstarted-button:hover {
            background: #a4adb9;
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: px;
            margin-bottom: 80px;
        }

        .brand-logo {
            width: 140px;
            margin-bottom: 0; /* Remove bottom margin */
            margin-right: 20px; /* Add right margin for spacing */
        }

        .brand-name {
            color: white;
            font-size: 72px;
            font-weight: bold;
            display: inline-block; /* Make it inline with the logo */
        }

        @media (max-width: 768px) {
            .brand-name {
                font-size: 40px;
            }

            .cards-container {
                gap: 20px;
            }

            .card {
                padding: 30px;
            }
        }
    </style>

    <div class="getstarted-container">
        <div class="brand-section">
            <img src="{{ asset('images/XPENSELosgz (2).png') }}" alt="Xpense Logo" class="brand-logo">
            <div class="brand-name">Xpense</div>
        </div>

        <div class="cards-container">
            <div class="card">
                <h2>Take Control of Your Finances</h2>
                <p>Start tracking your expenses, saving money, and achieving your financial goals.</p>
            </div>

            <div class="card">
                <h2>Achieve Financial Problem</h2>
                <p>Get the tools and insights you need to make smart financial decisions, save money, and build a secure future.</p>
            </div>
        </div>

        <a href="{{ route('login') }}" class="getstarted-button">
            Get Started
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>
</x-guest-layout>
