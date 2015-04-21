<?php

namespace ByJG;

class FonemaBR
{

	public function converte($text)
	{ 
		$text = strtoupper(utf8_decode($text)); // padroniza todas as letras em maiusculo
		$vog = array("A", "E", "I", "O", "U", "a", "e", "i", "o", "u");
		$result = "";

		for ( $i=0 ; $i < strlen($text); $i++)
		{ // percorre toda a string
			$previous = isset($text[$i-1]) ? $text[$i-1] : '^';
			$current = $text[$i];
			$next = isset($text[$i+1]) ? $text[$i+1] : '$';

			// Aqui é feita a validação
			switch($current)
			{ 
				case ' ':
					$result .= ' ';
					break;
				case 'B':
				case 'b':
					$result .= 'B';
					break;
				case 'C':
				case 'c':
					if ($next == 'H')
					{
						$result .= 'X';
						$i++;
					}
					elseif(($next == 'E') || ($next == 'I'))
					{
						$result .= 'SS';
						$i++;
					}
					else
					{
						$result .= 'K';
					}
					break;
				case 'Ç':
				case 'ç':
				case chr(231): // Cedilha minusculo
				case chr(199): // Cedilha maiúsculo
					$result .= 'SS';
					break;
				case 'D':
				case 'd':
					$result .= 'D';
					break;
				case "E":
				case "e":
					if ($i==0 && ($next == 'S' || $next == 'X'))
					{
						$result .= 'X';
						$i++;
					}
					break;
				case 'F':
				case 'f':
					$result .= 'F';
					break;
				case 'G':
				case 'g':
					$result .= 'G';
					break;
				case 'J':
				case 'j':
					$result .= 'J';
					break;
				case 'K':
				case 'k':
					$result .= 'K';
					break;
				case 'L':
				case 'l':
					$result .= 'L';
					break;
				case 'M':
				case 'm':
					$result .= 'M';
					break;
				case 'N':
				case 'n':
					if (($next == '$') || (!in_array($next, $vog) && $next != 'H') )
					{
						$result .= 'M';
					}
					else
					{
						$result .= 'N';
					}
					break;
				case 'P':
				case 'p':
					if ($next == 'H')
					{
						$result .= 'F';
						$i++;
					}
					else
					{
						$result .= 'P';
					}
					break;
				case 'Q':
				case 'q':
					$result .= 'K';
					break;
				case 'R':
				case 'r':
					if ($next == "$" && in_array($previous, $vog))
					{
						$result .= '';
						$i++;
					}
					else
					{
						$result .= 'R';
					}
					break;
				case 'S':
				case 's':
					if ($next == 'H')
					{
						$result .= 'X';
						$i++;
					}
					elseif ($next == 'S')
					{
						$result .= 'SS';
						$i++;
					}
					elseif (($i == 0) && (in_array($next, $vog)))
					{
						$result .= 'SS';
						$i++;
					}
					else
					{
						$result .= 'Z';
					}
					break;
				case 'T':
				case 't':
					$result .= 'T';
					break;
				case 'V':
				case 'v':
					$result .= 'V';
					break;
				case 'X':
				case 'x':
					$result .= 'X';
					break;
				case 'W':
				case 'w':
					if (in_array($next, $vog))
					{
						$result .= 'V';
						$i++;
					}
					break;
				case 'Z':
				case 'z':
					if (!in_array($next, $vog))
					{
						$result .= 'S';
						$i++;
					}
					else if ($next == '')
					{
						$result .= 'S';
						$i++;
					}
					else
					{
						$result .= 'Z';
					}
					break;

				default:
					break;

			} // END_SWITCH
		} // END _FOR

		return $result;
	} // END_METHOD

}
