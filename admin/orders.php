<?php
session_start();
include 'C:\xampp\htdocs\furniture\includes\db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: /furniture/login.php");
    exit;
}

// Handle AJAX request to update order status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $valid_statuses = ['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled'];

    if (in_array($status, $valid_statuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true, 'status' => $status]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }
}

// Fetch all orders
$query = "
    SELECT orders.id AS order_id, orders.status, orders.created_at, users.username, 
    order_items.furniture_id, order_items.quantity, order_items.price, 
    furniture.name AS furniture_name
    FROM orders
    LEFT JOIN order_items ON orders.id = order_items.order_id
    LEFT JOIN furniture ON order_items.furniture_id = furniture.id
    LEFT JOIN users ON orders.user_id = users.id
    ORDER BY orders.created_at DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'C:\xampp\htdocs\furniture\includes\head.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .status-pending {
            border-color: orange;
            border-width: 2px;
        }

        .status-confirmed {
            border-color: blue;
            border-width: 2px;
        }

        .status-shipped {
            border-color: purple;
            border-width: 2px;
        }

        .status-delivered {
            border-color: green;
            border-width: 2px;
        }

        .status-cancelled {
            border-color: red;
            border-width: 2px;
        }
    </style>

</head>

<body class="min-h-screen flex">
    <aside class="w-72 bg-white shadow-sm">
        <?php include 'C:\xampp\htdocs\furniture\includes\sidebar.php'; ?>
    </aside>

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-4">All Orders</h1>

        <table class="min-w-full bg-white border border-gray-300">
            <thead class="text-sm">
                <tr>
                    <th class="border px-4 py-2">Order ID</th>
                    <th class="border px-4 py-2">User</th>
                    <th class="border px-4 py-2">Furniture</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Price</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Order Date</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['furniture_name']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td class="border px-4 py-2"><?php echo "$" . number_format($row['price'], 2); ?></td>
                        <td class="border px-4 py-2">
                            <select class="order-status px-2 py-1 border" data-order-id="<?php echo $row['order_id']; ?>">
                                <?php
                                $statuses = ['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled'];
                                foreach ($statuses as $status) {
                                    $selected = ($row['status'] == $status) ? 'selected' : '';
                                    echo "<option value='$status' $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td class="border px-4 py-2"><?php echo date("Y-m-d H:i:s", strtotime($row['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <script>
        $(document).ready(function() {
            // Function to update border color
            function updateBorderColor(selectElem) {
                let status = selectElem.val();
                selectElem.removeClass('status-pending status-confirmed status-shipped status-delivered status-cancelled');

                switch (status) {
                    case 'Pending':
                        selectElem.addClass('status-pending');
                        break;
                    case 'Confirmed':
                        selectElem.addClass('status-confirmed');
                        break;
                    case 'Shipped':
                        selectElem.addClass('status-shipped');
                        break;
                    case 'Delivered':
                        selectElem.addClass('status-delivered');
                        break;
                    case 'Cancelled':
                        selectElem.addClass('status-cancelled');
                        break;
                }
            }

            // Set initial border colors based on current status
            $('.order-status').each(function() {
                updateBorderColor($(this));
            });

            // Change border color when status is changed
            $('.order-status').change(function() {
                let order_id = $(this).data('order-id');
                let status = $(this).val();
                let selectElem = $(this);

                $.ajax({
                    url: '/furniture/admin/orders.php',
                    method: 'POST',
                    data: {
                        order_id: order_id,
                        status: status
                    },
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.success) {
                            location.reload();
                        } else {
                            alert('Error updating status: ' + res.message);
                        }
                    },
                    error: function() {
                        alert('Error processing request.');
                    }
                });

                // Update border color immediately
                updateBorderColor(selectElem);
            });
        });
    </script>

</body>

</html>