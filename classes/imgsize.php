<?php

/////////////////////////////////////////////////////////////////////////////////
//
//  PHP ImgSize Class v1.03b  (2001-04-11)
//  Copyright (c) 2001, Digitek Design/Chris Allen
//  http://www.digitekdesign.com/software/
//  Email: development@digitekdesign.com
//
//  This program is free software; you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation; either version 2 of the License, or
//  (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
/////////////////////////////////////////////////////////////////////////////////

class ImgSize {

    var $VERSION = "1.03b";
    var $PHP405 = FALSE;
    var $ISTEMPFILE = FALSE;
    var $ISURL = FALSE;
    var $ERROR = "";

    var $BUFSIZE = 1024;
    var $TEMPDIR = "/tmp";

    var $width = -1;
    var $height = -1;
    var $type = "";
    var $channels = -1;
    var $depth = -1;
    var $mode = "";

	var $types = array(
        '^\x0A[\x00,\x02,\x03,\x05]'    => "PCX",
		'^II\x2a\x00'                   => "TIF",
		'^MM\x00\x2a'                   => "TIF",
		'\#define\s+\S+\s+\d+'          => "XBM",
		'\/\* XPM \*\/'                 => "XPM",
        '^8BPS'                         => "PSD",
        "^P[1-6]"                       => "PPM",
		'^BM'                           => "BMP"
	);

    var $PHPTYPES = array("","GIF","JPG","PNG","SWF");

    function imgsize ($f) {
        // See if this version of PHP supports urls in GetImageSize
        $this->checkphpversion();

        if (empty($f)) {
            $this->ERROR = "No Image Specified";
            return FALSE;
        }

        if (strtolower(substr($f,0,7)) == 'http://' || strtolower(substr($f,0,6)) == 'ftp://') {
            $this->ISURL = TRUE;

            if (!$this->PHP405) {
                // Not using PHP 4.0.5+ -- gotta download the file first
                if (!($f = $this->fetchimage($f))) {
                    $this->ERROR = "Unable to fetch image";
                    return FALSE;
                }
            }
        }

        if (!$this->ISURL && !is_readable($f)) {
            $this->ERROR = "Cannot open image";
            return FALSE;
        }

        // Attempt to determine image size using PHP internal methods
        if($rt = @GetImageSize($f)) {
            $this->width = $rt[0];
            $this->height = $rt[1];
            $this->type = $this->PHPTYPES[$rt[2]];
            if($this->ISTEMPFILE) { unlink ($f); }
            return FALSE;
        }

        // Image must not be a valid GIF/JPG/PNG/SWF file so we have to process it
        $rt = $this->MyGetImageSize($f);
        if($this->ISTEMPFILE) { unlink ($f); }
        if (!$rt) return FALSE;
        return;
    }

    function MyGetImageSize ($f) {
        // Open file
        if (!($fp = @fopen($f,"r"))) {
            $this->ERROR = "Cannot open image";
            return FALSE;
        }

        // Figure out the file type
        $buf = fread($fp,10);

        while (list($a,$b) = each($this->types)) {
            if (preg_match("/$a/",$buf)) { $this->type = $b; }
        }
        if (empty($this->type)) {
            $this->type = "Unknown";
            $this->ERROR = "Unknown Image Type";
            return FALSE;
        }

        switch ($this->type) {
            case "BMP":
                fseek($fp,14,SEEK_SET);
                $ty = $this->myunpack("V",fread($fp,4));
                if ($ty == 40) {
        		    $rt = unpack("Vwidth/Vheight", fread($fp, 8));  // Windows
                } else {
            		$rt = unpack("vwidth/vheight", fread($fp, 4));  // OS/2
                }
                $this->width = $rt[width]; $this->height = $rt[height];
                break;
            case "PCX":
                fseek($fp,4,SEEK_SET);
                $ar = unpack("vxmin/vymin/vxmax/vymax", fread($fp,8));
                $this->width = $ar[xmax]-$ar[xmin]+1;
                $this->height = $ar[ymax]-$ar[ymin]+1;
                break;
            case "TIF":
                if (preg_match('/MM\x00\x2a/', $buf)) { $en = "n"; } else {$en = "v";}
                $enu = strtoupper($en);

                $specs = array(0,"C",0,$en,$enu,0,"c",0,$en,$enu);
                fseek($fp,4);
                $ofs = $this->myunpack($enu, fread($fp,4));
                fseek($fp,$ofs);
                $de = $this->myunpack($en, fread($fp, 4));
                $ofs += 2;
                $de = $ofs + ($de * 12);

    		    while (!isset($nx) || !isset($ny)) {
    	    		fseek($fp, $ofs);
    		    	$ifd = fread($fp, 12);
    			    if (($ifd == "")||($ofs > $de))
    				    break;
        			$ofs += 12;
    	    		$tag = $this->myunpack($en, $ifd);
    			    $type = $this->myunpack($en, substr($ifd, 2, 2));

    		    	if (($type > count($specs))) {
    			    	continue;
                    }
        			if ($tag == 0x0100) {
    		    		$x = $this->myunpack($specs[$type], substr($ifd, 8, 4));
        			} elseif ($tag == 0x0101) {
    		    		$y = $this->myunpack($specs[$type], substr($ifd, 8, 4));
        			}
    	    	}
    		    if (isset($x) && isset($y)) {
    	    		$this->width = $x;
    		    	$this->height = $y;
        		}
                break;
            case "PPM":
                fseek($fp,0,SEEK_SET);
                $hdr = fread($fp, 1024);
        		preg_match("/^(P[1-7])\s+(\d+)\s+(\d+)/s", $hdr, $r);
                $this->width = $r[2];
                $this->height = $r[3];
                switch($r[1]) {
                    case "P1":
                    case "P4":
                        $this->type = "PBM";
                        break;
                    case "P2":
                    case "P5":
                        $this->type = "PGM";
                        break;
                    case "P3":
                    case "P6":
                        $this->type = "PPM";
                        break;
                    $this->height = $r[2];
                    break;
    		    }
                break;
            case "XBM":
    		    $hdr = fread($fp, 1024);
    		    if (preg_match("/^\#define\s*\S*\s*(\d+)\s*\n\#define\s*\S*\s*(\d+)/si", $hdr, $r)) {
    			    $this->width = $r[1];
    			    $this->height = $r[2];
                }
                break;
            case "XPM":
    		    while ($buf = fread($fp, 1024)) {
    			    if (!preg_match("/\"\s*(\d+)\s+(\d+)(\s+\d+\s+\d+){1,2}\s*\"/s", $buf, $r))
        				continue;
    	    		else {
    		    		$this->width = $r[1];
    			    	$this->height = $r[2];
    				    break;
        			}
    	    	}
                break;
            case "PSD":
                fseek($fp,12,SEEK_SET);
                $ar = unpack("nc/Ny/Nx/nd/nm", fread($fp,14));
                $this->channels = $ar[c];
                $this->width = $ar[x];
                $this->height = $ar[y];
                $this->depth = $ar[d];
                switch($ar[m]) {
                    case 0:
                        $this->mode = "BITMAP";
                        break;
                    case 1:
                        $this->mode = "GRAYSCALE";
                        break;
                    case 2:
                        $this->mode = "INDEXED";
                        break;
                    case 3:
                        $this->mode = "RGB";
                        break;
                    case 4:
                        $this->mode = "CMYK";
                        break;
                    case 7:
                        $this->mode = "MULTICHANNEL";
                        break;
                    case 8:
                        $this->mode = "DUOTONE";
                        break;
                    case 9:
                        $this->mode = "LAB";
                        break;
                }
                break;
        }
        fclose($fp);
    }

    // Define Internal Functions
    function myunpack($t,$s) {
        $x = unpack($t."x",$s);
        return $x[x];
    }

    function checkphpversion() {
        $v = phpversion();

        // strip off -dev ,etc....
        if ($a = strchr($v,'-')) $v = substr($v,0,strlen($v)-strlen($a));

        // split and check
        $v = explode('.',$v);
        if ($v[0] >= 4 && $v[1] >= 0 && $v[2] >= 5)
            $this->PHP405 = TRUE;
        else
            $this->PHP405 = FALSE;
    }

    function fetchimage($f) {
        if (($fp = @fopen($f,"r")) == FALSE) return FALSE;

        $n = tempnam($this->TEMPDIR,"img");
        if (($wp = @fopen($n,"w")) == FALSE) {
            fclose($fp); return FALSE;
        }
        while (!feof($fp))
            fwrite($wp,fread($fp,$this->BUFSIZE));

        fclose($fp);
        fclose($wp);
        return $n;
    }
} // end class

?>
