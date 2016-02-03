<?php
require('../pdf/fpdf.php');

class FPDF2 extends FPDF
{
    var $B;
    var $I;
    var $U;
    var $HREF;

    function FPDF2($orientation='P', $unit='mm', $size='A4')
    {
        // Appel au constructeur parent
        $this->FPDF($orientation,$unit,$size);
        // Initialisation
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
    }

    function WriteHTML($html)
    {
        // Parseur HTML
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        //var_dump($a);
        $cpt = 0;
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Texte
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Balise
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extraction des attributs
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr, $a[$cpt + 1]);
                }
            }
            $cpt++;
        }
    }

    function OpenTag($tag, $attr, $txt)
    {
        // Balise ouvrante
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
        if($tag=='C'){
            $x = (200-$this->GetStringWidth($txt))/2;
            $this->setX($x);
        }
    }

    function CloseTag($tag)
    {
        // Balise fermante
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modifie le style et sélectionne la police correspondante
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }
    
    function PutLink($URL, $txt)
    {
        // Place un hyperlien
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}

?>
