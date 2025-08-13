<?php
declare(strict_types=1);

use MiniStore\Modules\Core\Autoloader;
use MiniStore\Modules\Core\Config;
use MiniStore\Modules\Products\Product;
use MiniStore\Modules\Users\Customer;
use MiniStore\Modules\Orders\Order;
use MiniStore\Modules\Payments\PayPalGateway;
use MiniStore\Modules\Payments\StripeGateway;

require __DIR__ . '/src/Modules/Core/Autoloader.php';
Autoloader::register();
Config::load(__DIR__ . '/config.php');

$currency = Config::get('CURRENCY', 'USD');

try {
    // 1) إنشاء منتجات
    $p1 = new Product('SKU-100', 'USB-C Cable', 8.50, 20);
    $p2 = new Product('SKU-200', 'Wireless Mouse', 17.99, 10);
    $p3 = new Product('SKU-300', 'Laptop Stand', 29.95, 5);

    // 2) إنشاء عميل
    $customer = new Customer(1, 'Marya Alshameri', 'marya@example.com');

    // 3) إنشاء طلب وربط المنتجات
    $order = new Order($customer);
    $order->addItem($p1, 2);
    $order->addItem($p2, 1);
    $order->addItem($p3, 1);

    // 4) تطبيق خصم خاص بالطلب (بالإضافة للخصم العام من config)
    $order->setDiscountPercent(0.10); // 10% إضافية

    // 5) حساب الإجماليات
    $subtotal   = $order->getSubtotal();
    $afterDisc  = $order->getTotalAfterDiscountBeforeTax();
    $taxAmount  = $order->getTaxAmount();
    $grandTotal = $order->getGrandTotal();

    // 6) معالجة الدفع (اختر إحدى البوابات)
    $gateway = new PayPalGateway('merchant@demo.test');
    // $gateway = new StripeGateway('sk_demo_123456789');

    $paid = $order->processPayment($gateway);

} catch (Throwable $e) {
    // في حال حدوث خطأ (مثلاً مخزون غير كافٍ)
    $error = $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>MiniStore Demo</title>
    <style>
        body { font-family: sans-serif; margin: 24px; }
        table { border-collapse: collapse; width: 600px; max-width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: left; }
        .ok { color: green; }
        .fail { color: red; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1>MiniStore — Demo</h1>

    <?php if (isset($error)) : ?>
        <p class="fail"><strong>Error:</strong> <?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php else: ?>
        <p>Customer: <strong><?= htmlspecialchars($customer->getName(), ENT_QUOTES) ?></strong> (<?= htmlspecialchars($customer->getEmail(), ENT_QUOTES) ?>)</p>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order->getItems() as $i => $item): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($item->getProduct()->getName(), ENT_QUOTES) ?></td>
                        <td><?= $item->getQuantity() ?></td>
                        <td><?= number_format($item->getProduct()->getPrice(), 2) . " $currency" ?></td>
                        <td><?= number_format($item->getSubtotal(), 2) . " $currency" ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="muted">Subtotal</th>
                    <th><?= number_format($subtotal, 2) . " $currency" ?></th>
                </tr>
                <tr>
                    <th colspan="4" class="muted">After Discounts</th>
                    <th><?= number_format($afterDisc, 2) . " $currency" ?></th>
                </tr>
                <tr>
                    <th colspan="4" class="muted">Tax (<?= (int)(Config::get('TAX_RATE',0)*100) ?>%)</th>
                    <th><?= number_format($taxAmount, 2) . " $currency" ?></th>
                </tr>
                <tr>
                    <th colspan="4">Grand Total</th>
                    <th><?= number_format($grandTotal, 2) . " $currency" ?></th>
                </tr>
            </tfoot>
        </table>

        <p>Status:
            <?php if ($paid): ?>
                <strong class="ok"><?= htmlspecialchars($order->getStatus(), ENT_QUOTES) ?></strong>
            <?php else: ?>
                <strong class="fail"><?= htmlspecialchars($order->getStatus(), ENT_QUOTES) ?></strong>
            <?php endif; ?>
        </p>

        <p class="muted">Log file: <code><?= htmlspecialchars(\MiniStore\Modules\Core\Config::get('LOG_PATH'), ENT_QUOTES) ?></code></p>
    <?php endif; ?>
</body>
</html>
