<?php

/**
 * Class SampleUseController
 *
 * @version 1.0
 * @author  Bishal Budhapirthi <bishalbudhapirthi@gmail.com>
 * @created 2018-09-14
 */
class SampleUseController
{

    /**
     * Creates new product from the post variable received from the post
     *
     * Assuming that this function is triggered by ajax request
     *
     */
    public function createNewProduct()
    {
        $objProduct =  new Product();

        $objProduct->setProductCode($_POST['product code'])
            ->setBarcode($_POST['barcode'])
            ->setProductDescription($_POST['product description'])
            ->setProductPrice($_POST['product price'])
            ->setDateCreated($_POST['date created']);

        if ($objProduct->save()){
            echo json_encode(['result' => 'failed', 'message' => 'Error occurred', $objProduct->getArrErrors()]);
            exit;
        }

        echo json_encode(['result' => 'success', 'message' => 'Successfully created new product']);

    }

}
