<?php
	/**
	* 
	*/
	class mTextCounter extends CI_Model
	{
		
		public function __construct(){
			parent::__construct();
			$this->load->library('TextStatistics/Syllables');

		}

		static public $arrProblemWords = array(
         'abalone'          => 4
        ,'abare'            => 3
        ,'abed'             => 2
        ,'abruzzese'        => 4
        ,'abbruzzese'       => 4
        ,'aborigine'        => 5
        ,'acreage'          => 3
        ,'adame'            => 3
        ,'adieu'            => 2
        ,'adobe'            => 3
        ,'anemone'          => 4
        ,'apache'           => 3
        ,'aphrodite'        => 4
        ,'apostrophe'       => 4
        ,'ariadne'          => 4
        ,'cafe'             => 2
        ,'calliope'         => 4
        ,'catastrophe'      => 4
        ,'chile'            => 2
        ,'chloe'            => 2
        ,'circe'            => 2
        ,'coyote'           => 3
        ,'epitome'          => 4
        ,'forever'          => 3
        ,'gethsemane'       => 4
        ,'guacamole'        => 4
        ,'hyperbole'        => 4
        ,'jesse'            => 2
        ,'jukebox'          => 2
        ,'karate'           => 3
        ,'machete'          => 3
        ,'maybe'            => 2
        ,'people'           => 2
        ,'recipe'           => 3
        ,'sesame'           => 3
        ,'shoreline'        => 2
        ,'simile'           => 3
        ,'syncope'          => 3
        ,'tamale'           => 3
        ,'yosemite'         => 4
        ,'daphne'           => 2
        ,'eurydice'         => 4
        ,'euterpe'          => 3
        ,'hermione'         => 4
        ,'penelope'         => 4
        ,'persephone'       => 4
        ,'phoebe'           => 2
        ,'zoe'              => 2
	    );

		static public $arrSubSyllables = array(
	         'cia(l|$)' // glacial, acacia
	        ,'tia'
	        ,'cius'
	        ,'cious'
	        ,'[^aeiou]giu'
	        ,'[aeiouy][^aeiouy]ion'
	        ,'iou'
	        ,'sia$'
	        ,'eous$'
	        ,'[oa]gue$'
	        ,'.[^aeiuoycgltdb]{2,}ed$'
	        ,'.ely$'
	        //,'[cg]h?ed?$'
	        //,'rved?$'
	        //,'[aeiouy][dt]es?$'
	        //,'^[dr]e[aeiou][^aeiou]+$' // Sorts out deal, deign etc
	        //,'[aeiouy]rse$' // Purse, hearse
	        ,'^jua'
	        //,'nne[ds]?$' // canadienne
	        ,'uai' // acquainted
	        ,'eau' // champeau
	        //,'pagne[ds]?$' // champagne
	        //,'[aeiouy][^aeiuoytdbcgrnzs]h?e[rsd]?$'
	        // The following detects words ending with a soft e ending. Don't
	        // mess with it unless you absolutely have to! The following
	        // is a list of words you can use to test a new version of
	        // this rule (add 'r', 's' and 'd' where possible to test
	        // fully):
	        //   - absolve
	        //   - acquiesce
	        //   - audience
	        //   - ache
	        //   - acquire
	        //   - brunelle
	        //   - byrne
	        //   - canadienne
	        //   - coughed
	        //   - curved
	        //   - champagne
	        //   - designate
	        //   - force
	        //   - lace
	        //   - late
	        //   - lathe
	        //   - make
	        //   - relayed
	        //   - scrounge
	        //   - side
	        //   - sideline
	        //   - some
	        //   - wide
	        //   - taste
	        ,'[aeiouy](b|c|ch|d|dg|f|g|gh|gn|k|l|ll|lv|m|mm|n|nc|ng|nn|p|r|rc|rn|rs|rv|s|sc|sk|sl|squ|ss|st|t|th|v|y|z)e$'
	        // For soft e endings with a "d". Test words:
	        //   - crunched
	        //   - forced
	        //   - hated
	        //   - sided
	        //   - sidelined
	        //   - unexploded
	        //   - unexplored
	        //   - scrounged
	        //   - squelched
	        //   - forced
	        ,'[aeiouy](b|c|ch|dg|f|g|gh|gn|k|l|lch|ll|lv|m|mm|n|nc|ng|nch|nn|p|r|rc|rn|rs|rv|s|sc|sk|sl|squ|ss|th|v|y|z)ed$'
	        // For soft e endings with a "s". Test words:
	        //   - absences
	        //   - accomplices
	        //   - acknowledges
	        //   - advantages
	        //   - byrnes
	        //   - crunches
	        //   - forces
	        //   - scrounges
	        //   - squelches
	        ,'[aeiouy](b|ch|d|f|gh|gn|k|l|lch|ll|lv|m|mm|n|nch|nn|p|r|rn|rs|rv|s|sc|sk|sl|squ|ss|st|t|th|v|y)es$'
	        ,'^busi$'
	    );

	    static public $arrAddSyllables = array(
	         '([^s]|^)ia'
	        ,'riet'
	        ,'dien' // audience
	        ,'iu'
	        ,'io'
	        ,'eo($|[b-df-hj-np-tv-z])'
	        ,'ii'
	        ,'[ou]a$'
	        ,'[aeiouym]bl$'
	        ,'[aeiou]{3}'
	        ,'[aeiou]y[aeiou]'
	        ,'^mc'
	        ,'ism$'
	        ,'asm$'
	        ,'thm$'
	        ,'([^aeiouy])\1l$'
	        ,'[^l]lien'
	        ,'^coa[dglx].'
	        ,'[^gq]ua[^auieo]'
	        ,'dnt$'
	        ,'uity$'
	        ,'[^aeiouy]ie(r|st|t)$'
	        ,'eings?$'
	        ,'[aeiouy]sh?e[rsd]$'
	        ,'iell'
	        ,'dea$'
	        ,'real' // real, cereal
	        ,'[^aeiou]y[ae]' // bryan, byerley
	        ,'gean$' // aegean
	        ,'uen' // influence, affluence
	    );

	    static public $arrAffix = array(
	         '`^un`'
	        ,'`^fore`'
	        ,'`^ware`'
	        ,'`^none?`'
	        ,'`^out`'
	        ,'`^post`'
	        ,'`^sub`'
	        ,'`^pre`'
	        ,'`^pro`'
	        ,'`^dis`'
	        ,'`^side`'
	        ,'`ly$`'
	        ,'`less$`'
	        ,'`some$`'
	        ,'`ful$`'
	        ,'`ers?$`'
	        ,'`ness$`'
	        ,'`cians?$`'
	        ,'`ments?$`'
	        ,'`ettes?$`'
	        ,'`villes?$`'
	        ,'`ships?$`'
	        ,'`sides?$`'
	        ,'`ports?$`'
	        ,'`shires?$`'
	        ,'`tion(ed)?$`'
	    );

	    static public $arrDoubleAffix = array(
	         '`^above`'
	        ,'`^ant[ie]`'
	        ,'`^counter`'
	        ,'`^hyper`'
	        ,'`^afore`'
	        ,'`^agri`'
	        ,'`^in[ft]ra`'
	        ,'`^inter`'
	        ,'`^over`'
	        ,'`^semi`'
	        ,'`^ultra`'
	        ,'`^under`'
	        ,'`^extra`'
	        ,'`^dia`'
	        ,'`^micro`'
	        ,'`^mega`'
	        ,'`^kilo`'
	        ,'`^pico`'
	        ,'`^nano`'
	        ,'`^macro`'
	        ,'`berry$`'
	        ,'`woman$`'
	        ,'`women$`'
	    );

	    static public $arrTripleAffix = array(
	         '`ology$`'
	        ,'`ologist$`'
	        ,'`onomy$`'
	        ,'`onomist$`'
	    );

		public function count_total_syllables($str){
			echo Syllables::totalSyllables($str)."<br/>";
		}

	}

?>