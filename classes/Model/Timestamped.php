<?php
abstract class Model_Timestamped extends Model_Ordered
{
    public function save()
    {
        $this->modified = time();
        if (!$this->getId())
            $this->created = $this->modified;
        parent::save();
    }
}
?>
