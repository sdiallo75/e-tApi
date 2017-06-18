<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Currency;
use AppBundle\Form\CurrencyType;
use FOS\RestBundle\Controller\FOSRestController;

class CurrencyController extends FOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Get("/currencies/{id}")
     */
    public function getCurrencyAction(Request $request)
    {
        $currency = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Currency')
            ->find($request->get('id'));

        if (null === $currency) {
            return \FOS\RestBundle\View\View::create(['message' => 'Currency not found'], Response::HTTP_NOT_FOUND);
        }
        
        return $currency;
    }
    
    /**
     * @Rest\View()
     * @Rest\Get("/currencies")
     */
    public function getCurrenciesAction()
    {        
        $currencies = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Currency')
            ->findAll();

        return $currencies;
    }
    
    /**
     * 
     * @param Request $request
     * 
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/currencies")
     */
    public function createCurrencyAction(Request $request)
    {
        $currency = new Currency();
        
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->submit($request->request->all());
        
        if ($form->isValid() === false) {
            return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        
        $em->persist($currency);
        $em->flush();

        return $currency;
    }
    
    /**
     * 
     * @Rest\View()
     * @Rest\Put("/currencies/{id}")
     * 
     * @param Request $request
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|object
     */
    public function putCurrencyAction(Request $request)
    {
        return $this->updateCurrency($request, true);
    }
    
    /**
     * 
     * @Rest\View()
     * @Rest\Patch("/currencies/{id}")
     * 
     * @param Request $request
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|object
     */
    public function patchCurrencyAction(Request $request)
    {
        return $this->updateCurrency($request, false);
    }
    
    /**
     * 
     * @param Request $request
     * 
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/currencies/{id}")
     */
    public function deleteCurrencyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
      
        $currency = $em->getRepository('AppBundle:Currency')
            ->find($request->get('id'));
            
        if (null !== $currency) {
            $em->remove($currency);
            $em->flush();
        }       
    }
    
    
    /**
     * 
     * @param Request $request
     * @param string $clearMissing
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form|object
     */
    public function updateCurrency(Request $request, $clearMissing = false)
    {
        $em = $this->getDoctrine()->getManager();
        
        $currency = $em->getRepository('AppBundle:Currency')
            ->find($request->get('id'));
        
        if (null === $currency) {
            return $this->view(['message' => 'Currency not found'], Response::HTTP_NOT_FOUND);
        }
        
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->submit($request->request->all(), $clearMissing);
        
        if (false === $form->isValid()) {
            return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
        }
      
        $em->persist($currency);
        $em->flush();
        
        return $currency;
    }
}
