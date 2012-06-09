<?php

/*
 * Function to get a BibleGateway Passage and return it according to the Bible and paramters
 * @since 7.1.0
 */

class showScripture
{
    function buildPassage($row, $params)
    {
        if (!$row->bname){return false;}
        
        $reference = $this->formReference($row);
        $version = $params->get('bible_version', '77');
        $this->link = $this->getBiblegateway($reference, $version); 
        $choice = $params->get('show_passage_view'); 
        switch ($choice)
        {
            case 0:
                $passage = '';
                
                break;
            
            case 1:
                $passage = $this->getHideShow($row, $reference);
                
                break;
            
            case 2:
                $passage = $this->getShow($row, $reference);
                
                break;
            
            case 3:
                $passage = $this->getLink($row, $reference);
               
                break;
        }
        return $passage;
    }
    
    function getHideShow($row, $reference)
    {
        $passage = '<div class = passage>';
        $passage .= '<a class="heading" href="javascript:ReverseDisplay(\'scripture\')">>>'.JText::_('JBS_CMN_SHOW_HIDE_SCRIPTURE').'<<</a>';
        $passage .= '<div id="scripture" style="display: none;">';
        $passage .= file_get_contents($this->link);
        $passage .= '</div>';
        $passage .= '</div>';
        return $passage;
    }
    
    function getShow($row, $reference)
    {
        $passage = '<div class = "passage">'.file_get_contents($this->link).'</div>';
        return $passage;
    }
    
    function getLink($row, $reference)
    {
        $passage = '<div class = passage>';
        //$passage .= '<a href="#" onclick="';
        $passage .= '<a href="'.$this->link.'"';
        $passage .=" onclick=\"window.open(this.href,'mywindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=500'); return false;" ;
       // $rel = "{handler: 'iframe', size: {x: 800, y: 500}}";
        $passage .= '">'.JText::_('JBS_STY_CLICK_TO_OPEN_PASSAGE').'</a>';
        $passage .= '</div>'; 
        return $passage;
    }
    
    function formReference($row)
    {
        $book = JText::_($row->bname);
        $book = str_replace(' ','+',$book);
        $book = $book.'+';
        $reference = $book.$row->chapter_begin;
        if ($row->verse_begin)
            {
            $reference .= ':'.$row->verse_begin;
            }
        if ($row->chapter_end && $row->verse_end)
        {
            $reference .= '-'.$row->chapter_end.':'.$row->verse_end;
        }
        if ($row->verse_end && !$row->chapter_end)
        {
            $reference .= '-'.$row->verse_end;
        }
        return $reference;
    }
    
    function getBiblegateway($reference, $version)
    {
        $link = "http://classic.biblegateway.com/passage/index.php?search=".$reference.";&version=".$version.";&interface=print";
        return $link;
    }
}
?>