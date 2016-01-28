<?php
namespace mylibrary\HelloEndPoint\Controllers;

use BOTK\Core\Controller,
    BOTK\Core\HttpErrorException;
use mylibrary\HelloEndPoint\Models\About as AboutModel;

/*
 * This is an example of a controller that interacts with a model.
 * In this case controller manage the representation needed by requests
 * dynamically looking to model properties and methods.
 * 
 * This means that you can change the model ( adding or deleting properties and methods)
 * without changing this controller.
 * 
 * The implemented business logic is the following:
 * 
 * if a property name is specified in the route than the response is the property
 * value expose by AboutModel.
 * 
 * if no property specified then is checked the get variable "process" to execute a
 * method expose by AboutModel.
 * 
 * Otherwhise an exception is trown.
 * 
 * Note that exception and representation rendering are managed in router
 * 
 */
final class AboutController extends Controller
{
    public function get($property = null)
    {
        // Instantiate model
        $exampleModel = new AboutModel;

        if (empty($property)) {// this if no [title|version|numbers] specified
            // Manage 'process' parameter
            $process = filter_input(INPUT_GET, 'process', FILTER_SANITIZE_STRING);
                        
            if ($process && !method_exists($exampleModel, $process)) {
                throw new HttpErrorException(HttpProblem::factory( 
                400, "Invalid process $process.", 'Model method not existing in AboutModel.'));
            }
            
            if ($process) {
                // Call model method
                $processResult = $exampleModel->$process();
                
                //  buld response body
                $result = $process ? array('data' => $exampleModel, $process => $processResult) : $exampleModel;
            } else {
                $result = $exampleModel;
            }
        } elseif (isset($exampleModel->$property)) {
            $result = $exampleModel->$property;
        } else {
            throw new HttpErrorException(HttpProblem::factory( 
                404, 'Property not found', "Invalid propery $property in AboutModel."));
        }
        return $result;
    }
}
