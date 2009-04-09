<?php
interface Model_Validatable
{
    /**
     * validate model's data and return errors array as the result
     * of check. This errors in the array are stored
     * as attribute name => error description pairs.
     * If $attribute parameter is given, validate() should
     * check only attributes passed in $attributes list.
     * @todo implement it in models
     * @param array attributes to check
     * @return array with errors
     */
    public function validate(array $attributes = array());
}
?>
