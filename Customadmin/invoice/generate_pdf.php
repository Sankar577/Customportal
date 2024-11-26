<?php
include("../../lib/config.php");
require_once __DIR__ . '../../vendor/autoload.php';

if (isset($_POST['action']) && $_POST['action'] == 'generate_pdf') {
    $id = $_POST['id'];
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'c',
        'format' => 'A4',
        'default_font' => 'ProzaLibre-Medium'
    ]);

    $sql = "
        SELECT 
            a.*, 
            b.* 
        FROM 
            custom_payment_details a 
        JOIN 
            custom_invoice_details b 
        ON 
            a.order_id = b.order_id 
        WHERE 
            b.id = '" . $id . "'
    ";
    $res = $db_cms->select_query_with_row($sql);

    if ($res) {
        $orders = json_decode($res['products'], true);

        $html = '
        <div style="font-family: Arial, sans-serif; color: #333; padding: 20px;">
            <h1 style="text-align: left; font-size: 28px; margin-bottom: 10px; border-bottom: 2px solid #000;">INVOICE</h1>
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div>
                    <p><strong>Invoice Number:</strong> ' . htmlspecialchars($res['invoice_id']) . '</p>
                    <p><strong>Invoice Date:</strong> ' . htmlspecialchars(date("d/m/Y", strtotime($res['date']))) . '</p>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div>
                    <p style="font-weight: bold; margin-bottom: 10px;">Billing Information:</p>
                    
                    <p>Name: ' . htmlspecialchars($res['custome_name']) . '</p>
                    <p>Address: ' . htmlspecialchars($res['address']) . '</p>
                    <p>Email: ' . htmlspecialchars($res['email']) . '</p>
                </div>
             
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
                <thead>
                    <tr style="background-color: #333; color: #fff;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Description</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Quantity</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Unit Price</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($orders as $order) {
            $html .= '
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($order['product_name']) . '</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">' . htmlspecialchars($order['quantity']) . '</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">$' . number_format($order['unit_price'], 2) . '</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">$' . number_format($order['total_price'], 2) . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div style="text-align: right; font-size: 14px; margin-bottom: 20px;">
                <p><strong>Subtotal:</strong> $' . number_format($res['sub_total'], 2) . '</p>
               
                <p><strong>Tax:</strong> $' . number_format($res['tax'], 2) . '</p>
                <p style="font-size: 18px; font-weight: bold; color: #333;">Total: $' . number_format($res['final_total'], 2) . '</p>

                 <p style="font-size: 18px; font-weight: bold; color: #333;"><strong>Paid Amount:</strong> $' . number_format($res['paid_amount'], 2) . '</p>

                 <p style="font-size: 18px; font-weight: bold; color: #333;"><strong>Balance:</strong> $' . number_format($res['balance'], 2) . '</p>
            </div>

           
        </div>';

        $mpdf->WriteHTML($html);
        $pdfFileName = "invoice_" . $res['order_id'] . ".pdf";
        $pdfFilePath = __DIR__ . "/pdfs/" . $pdfFileName;

        if (!file_exists(__DIR__ . "/pdfs")) {
            mkdir(__DIR__ . "/pdfs", 0777, true);
        }

        $mpdf->Output($pdfFilePath, 'F');
        $downloadUrl = 'https://development.zerosoft.in/Customportal/Customadmin/pdfs/' . $pdfFileName;
        echo json_encode(['pdf_url' => $downloadUrl]);
    } else {
        echo json_encode(['error' => 'Invoice details not found.']);
    }
}
