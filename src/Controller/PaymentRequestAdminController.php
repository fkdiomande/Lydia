<?php

namespace App\Controller;

use App\Entity\PaymentRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PaymentRequestAdminController extends AbstractController
{
    /**
     * @Route("/admin/payment/request", name="admin_payment_request")
     */
    public function index()
    {
        $paymentRequestRepo = $this->getDoctrine()->getRepository(PaymentRequest::class);

        $paymentRequests = $paymentRequestRepo->findAll();

        return $this->render('payment_request_admin/index.html.twig', [
            'paymentRequests' => $paymentRequests,
            'vendorToken' => getenv('LYDIA_API_VENDOR_TOKEN'),
            'apiRequestStateUrl' => getenv('LYDIA_API_REQUEST_STATE_URL')
        ]);
    }
}
