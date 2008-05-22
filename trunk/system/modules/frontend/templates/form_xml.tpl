<?php echo '<?'; ?>xml version="1.0" encoding="<?php echo $this->charset; ?>" standalone="yes" ?>
<!DOCTYPE form [
  <!ELEMENT form (field)+>
  <!ELEMENT field (name, value+)>
  <!ELEMENT name (#PCDATA)>
  <!ELEMENT value (#PCDATA)>
]>
<form><?php foreach ($this->fields as $field): ?> 
  <field>
    <name><?php echo $field['name']; ?></name><?php foreach ($field['values'] as $value): ?> 
    <value><?php echo $value; ?></value><?php endforeach; ?> 
  </field><?php endforeach; ?> 
</form>