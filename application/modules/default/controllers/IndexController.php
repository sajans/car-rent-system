<?php

class Default_IndexController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        $form = new Admin_Form_BookingForm();
        $this->view->form = $form;
        $extraModel = new Admin_Model_Extra();
        $extras = $extraModel->getAll();
        $this->view->extra = $extras;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $formData['pickup_time'] = $formData['pickup_time'] . ":00:00";
                $formData['return_time'] = $formData['return_time'] . ":00:00";
                unset($formData['submit']);
                unset($formData['booking_id']);
                $session = Zend_Registry::get("defaultsession");
                $session->first = $formData;
                $this->_helper->redirector("info");
            }
        }
    }

    public function infoAction() {
        $form = new Admin_Form_InformationForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $session = Zend_Registry::get("defaultsession");
                $firstData = $session->first;
                $formData += $firstData;
//                $data= $formData['extra_id'];
//                echo"<pre>";
//                print_r($data);exit;
//                unset($formData["extra_id"]);
                unset($formData['submit']);
                try {
                    $bookingModel = new Admin_Model_Booking();
                    $bookingModel->add($formData);
                    $this->_helper->FlashMessenger->addMessage(array("success" => "Successfully Booking Completed"));
                    $this->_helper->redirector('index');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage(array("error" => $e->getMessage()));
                }
            }
        }
    }

}
