<?php

class IndexController extends Zend_Controller_Action {

    private $album;

    public function init() {
        /* Initialize action controller here */
        $this->album = new Application_Model_DbTable_Album();
    }

    public function indexAction() {
        // action body
    }

    public function addAction() {
        $form = new Application_Form_Album();
        $form->submit->setLabel('Add');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $artist = $form->getValue('artist');
                $title = $form->getValue('title');
                $this->album->addAlbum($artist, $title);
                $this->_helper->redirector('getalbum');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function getalbumAction() {
        $this->view->album = $this->album->fetchAll();
    }

    public function deleteAction() {
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');
                $this->album->deleteAlbum($id);
            }
            $this->_helper->redirector('getalbum');
        } else {
            $id = $this->_getParam('id', 0);
            $this->view->album = $this->album->getAlbum($id);
        }
    }

    public function updateAction() {
        $form = new Application_Form_Album();
        $form->submit->setLabel('Edit');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int) $form->getValue('id');
                $artist = $form->getValue('artist');
                $title = $form->getValue('title');
                $this->album->updateAlbum($id, $artist, $title);
                $this->_helper->redirector('getalbum');
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $form->populate($this->album->getAlbum($id));
            }
        }
    }

}
