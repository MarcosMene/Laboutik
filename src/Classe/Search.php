<?php

namespace App\Classe;

use Symfony\Component\Validator\Constraints as Assert;

class Search
{
  #[Assert\Length(
    max: 15,
    maxMessage: 'Votre recherche ne peut pas depasser plus de {{ limit }} caractères',
  )]
  #[Assert\Type(
    type: 'string',
    message: 'Vous devez entrer seulement des caractères.',
  )]
  /**
   *
   * @var string
   */
  public $string = '';

  /**
   *
   * @var Category[]
   */
  public $categories = [];
}
