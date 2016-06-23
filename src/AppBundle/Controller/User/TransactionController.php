<?php

namespace AppBundle\Controller\User;

use AppBundle\Controller\Helper;
use AppBundle\Form\TransactionType;
use AppBundle\Model\Dto\Transaction;
use AppBundle\Model\Entity\AccountEntity;
use AppBundle\Service\User\Accounter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TransactionEntity controller.
 *
 * @Route(path="/user/transaction", service="controller.user.transaction")
 */
class TransactionController
{

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var Accounter
     */
    private $accounter;

    /**
     * UserController constructor.
     * @param Helper $helper
     * @param Accounter $accounter
     */
    public function __construct(Helper $helper, Accounter $accounter)
    {
        $this->helper = $helper;
        $this->accounter = $accounter;
    }

    /**
     * Lists all AccountEntity entities.
     *
     * @Route("/account/{accountFromId}", name="user_transaction_account")
     * @ParamConverter("accountFrom", class="AppBundle:AccountEntity", options={"id" = "accountFromId"})
     * @Method({"GET","POST"})
     * @param Request $request
     * @param AccountEntity $accountFrom
     *
     * @return Response
     */
    public function accountTransactionAction(Request $request, AccountEntity $accountFrom)
    {
        $search = $request->get('search', '');
        $transactionForm = null;

        if ($search) {
            $transactionForm = $this->getTransactionForm($accountFrom, $search);
        }

        if ($request->isMethod(Request::METHOD_POST)) {
            $transactionForm->handleRequest($request);

            if ($transactionForm->isSubmitted() && $transactionForm->isValid()) {
                $transaction = $transactionForm->getData();
                $this->accounter->executeTransaction($transaction);

                return $this->helper->redirectToRoute(
                    'user_account_show',
                    ['id' => $accountFrom->getId()]
                );
            }
        }

        return $this->helper->render('user/transaction/account.html.twig', [
            'transactionForm' => $transactionForm ? $transactionForm->createView() : null,
            'accountFrom' => $accountFrom,
            'search' => $search,
        ]);
    }

    /**
     * @param AccountEntity $accountFrom
     * @param string $search
     * @return \Symfony\Component\Form\Form|null
     */
    private function getTransactionForm(AccountEntity $accountFrom, string $search = '')
    {
        $accountsTo = null;

        if ($search) {
            $accountsTo = $this->accounter->searchExclude($search, $accountFrom);

            if (empty($accountsTo)) {
                return null;
            }
        }

        return $this->helper->createForm(
            TransactionType::class,
            new Transaction($accountFrom),
            [
                'accountsTo' => $accountsTo,
                'action' => $this->helper->generateUrl(
                    'user_transaction_account',
                    [
                        'accountFromId' => $accountFrom,
                        'search' => $search,
                    ]
                ),
            ]
        );
    }
}
