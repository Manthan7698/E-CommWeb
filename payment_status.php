<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - E-CommWeb</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .success-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background-color: var(--secondary-color);
        }

        .success-card {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            position: relative;
            animation: scaleIn 0.5s ease-out 0.3s both;
        }

        .success-icon i {
            font-size: 50px;
            color: var(--white);
            animation: checkmark 0.5s ease-out 0.8s both;
        }

        .success-title {
            color: var(--primary-color);
            font-size: 32px;
            margin-bottom: 15px;
            animation: fadeIn 0.5s ease-out 0.5s both;
        }

        .success-message {
            color: var(--light-text);
            font-size: 18px;
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease-out 0.7s both;
        }

        .order-details {
            background: var(--secondary-color);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease-out 0.9s both;
        }

        .order-details p {
            margin: 10px 0;
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .order-details i {
            color: var(--primary-color);
        }

        .continue-shopping {
            background: var(--primary-color);
            color: var(--white);
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            animation: fadeIn 0.5s ease-out 1.1s both;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .continue-shopping:hover {
            background: #066963;
            transform: translateY(-2px);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        @keyframes checkmark {
            0% {
                opacity: 0;
                transform: scale(0);
            }
            50% {
                opacity: 1;
                transform: scale(1.2);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="success-title">Order Placed Successfully!</h1>
            <p class="success-message">Thank you for your purchase. Your order has been confirmed.</p>
            
            <div class="order-details">
                <p><i class="fas fa-receipt"></i> <strong>Order Number:</strong> #<?php echo isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : 'N/A'; ?></p>
                <p><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> <?php echo date('F j, Y'); ?></p>
                <p><i class="fas fa-check-circle"></i> <strong>Status:</strong> <span style="color: var(--primary-color);">Confirmed</span></p>
            </div>
            
            <a href="index.php" class="continue-shopping">
                <i class="fas fa-shopping-cart"></i> Continue Shopping
            </a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
