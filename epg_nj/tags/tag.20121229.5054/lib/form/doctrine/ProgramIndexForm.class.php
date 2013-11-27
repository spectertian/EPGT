<?php

/**
 * ProgramIndex form.
 *
 * @package    epg
 * @subpackage form
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProgramIndexForm extends BaseProgramIndexForm
{
  public function configure()
  {
              unset(
                $this['created_at'], $this['updated_at']
        );

  }
}
