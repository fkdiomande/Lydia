<?php

namespace App\Controller;

use App\Entity\PaymentRequest;
use App\Form\PaymentRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

class PaymentRequestController extends AbstractController
{
    /**
     * @Route("/payment-request", name="payment_request")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $paymentRequest = new PaymentRequest();
        $form = $this->createForm(PaymentRequestType::class, $paymentRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentRequest->setUniqueId(Uuid::uuid4());
            $em = $this->getDoctrine()->getManager();
            $em->persist($paymentRequest);
            $em->flush();
            $data = [
                'code' => Response::HTTP_OK,
                'error' => false,
                'data' => [
                    'uniqueId' => $paymentRequest->getUniqueId(),
                    'savePaymentRequestUuid' => $this->generateUrl('save_payment_request_uuid'),
                    'apiRequestDoUrl' => getenv('LYDIA_API_REQUEST_DO_URL'),
                    'apiParams' => [
                        'message' => $paymentRequest->getMessage(),
                        'amount' => $paymentRequest->getAmount(),
                        'currency' => 'EUR',
                        'type' => 'email',
                        'recipient' => $paymentRequest->getEmail(),
                        'vendor_token' => getenv('LYDIA_API_VENDOR_TOKEN'),
                        'delayed_payment' => '1'
                    ]
                ]
            ];

            return new JsonResponse($data, $data['code']);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            // Todo: return form errors
        }

        return $this->render('payment_request/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/save-payment-request-uuid", name="save_payment_request_uuid")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function savePaymentRequestUuid(Request $request)
    {

        $paymentRequestRepo = $this->getDoctrine()->getRepository(PaymentRequest::class);
        $paymentRequest = $paymentRequestRepo->findOneBy(['uniqueId' => $request->request->get('uniqueId')]);

        $paymentRequest->setError($request->request->get('error'));
        $paymentRequest->setRequestId($request->request->get('requestId'));
        $paymentRequest->setRequestUuid($request->request->get('requestUuid'));
        $this->getDoctrine()->getManager()->flush();

        $data = [
            'code' => Response::HTTP_OK,
            'error' => false,
        ];

        return new JsonResponse($data, $data['code']);
    }



}
