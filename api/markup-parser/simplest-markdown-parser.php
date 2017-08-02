<?php
/*!
 * =======================================================
 *  Author  : Taufik Nurrohman
 *  URL     : https://github.com/tovic
 *  License : MIT
 * =======================================================
 *
 * -- CODE: ----------------------------------------------
 *
 *     echo MD('this is a **bold** text');
 *
 * -------------------------------------------------------
 *
 */
// escape
function __MDE($str, $x) {
    return preg_replace('#([' . preg_quote($x, '#') . '])#', '\\\$1', $str);
}
// un-escape
function __MDD($str, $x) {
    return preg_replace('#\\\\([' . preg_quote($x, '#') . '])#', '$1', $str);
}
// main function
function MD($content) {
    // character(s) to escape
    $x = '`~!#^*()-_+={}[]:\'"<>.';
    // URL pattern
    $url = '(?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?\#\[\]@%]+';
    // empty element suffix
    $suffix = '>';
    // normalize white-space
    $content = trim(str_replace(array("\r\n", "\r"), "\n", $content));
    // parse fenced code block to indented code block
    $content = preg_replace_callback('#(^|\n)([`~]{3,})(?: *\.?([a-zA-Z0-9\-.]+))?\n+([\s\S]+?)\n+\2(\n|$)#', function($m) {
        $s = "\0" . str_replace('.', ' ', $m[3]) . "\0\n";
        return $m[1] . $s . '    ' . str_replace("\n", "\n    ", $m[4]) . $m[5];
    }, $content);
    // parse code block
    $content = preg_replace_callback('#^(?:\0(.*?)\0\n)?( {4}|\t)(.*?)$#m', function($m) use($x) {
        $s1 = ! empty($m[1]) ? ' class="' . $m[1] . '"' : "";
        $s3 = str_replace("\t", '    ', htmlentities($m[3], ENT_NOQUOTES));
        return '<pre><code' . $s1 . '>' . __MDE($s3, $x) . '</code></pre>';
    }, $content);
    // parse code inline
    $content = preg_replace_callback('#(?<!\\\)`([^\n]+?)`#', function($m) use($x) {
        $s = htmlentities($m[1], ENT_NOQUOTES);
        return '\\<code>' . __MDE($s, $x) . '</code>';
    }, $content);
    // parse image and link
    $content = preg_replace_callback('#(!)?\[(.*?)\]\((.*?)( +([\'"])(.*?)\5)?\)#', function($m) use($x, $suffix) {
        $s2 = $m[2];
        $s3 = __MDE($m[3], $x);
        $s6 = ! empty($m[4]) && ! empty($m[6]) ? __MDE($m[6], $x) : "";
        $s6 = $s6 ? ' title="' . htmlentities($s6) . '"' : "";
        if( ! empty($m[1])) {
            return '\\<img alt="' . htmlentities($s2) . '" src="' . $s3 . '"' . $s6 . $suffix;
        }
        return '\\<a href="' . $s3 . '"' . $s6 . '>' . $s2 . '</a>';
    }, $content);
    // parse link
    $content = preg_replace_callback('#<(' . $url . ')>#', function($m) use($x) {
        return '\\<a href="' . __MDE($m[1], $x) . '">' . $m[1] . '</a>';
    }, $content);
    // parse ATX header(s)
    $content = preg_replace_callback('#^(\#{1,6})\s*([^\#]+?)\s*\#*$#m', function($m) {
        $i = strlen($m[1]);
        return '<h' . $i . '>' . $m[2] . '</h' . $i . '>';
    }, $content);
    $content = preg_replace(
        array(
            // parse SEText header(s)
            '#^(.+?)\n={2,}$#m',
            '#^(.+?)\n-{2,}$#m',
            // parse horizontal rule
            '#^ {0,3}([*\-+] *){3,}$#m',
            // parse bold-italic text
            '#(?<!\\\)([*_]{2})([*_])([^\n]+?)\2\1#',
            // parse bold text
            '#(?<!\\\)([*_]{2})([^\n]+?)\1#',
            // parse italic text
            '#(?<!\\\)([*_])([^\n]+?)\1#',
            // parse strike text
            // '#(?<!\\\)(~{2})([^\n]+?)\1#',
            // parse unordered-list
            '#^ *[*\-+] +(.*?)$#m',
            // parse ordered-list
            '#^ *\d+\. +(.*?)$#m',
            // parse quote block
            '#^(?:>|&gt;) +(.*?)$#m',
            // clean-up list ...
            '#\s*<\/(ol|ul)>\n<\1>\s*#',
            // clean-up quote block ...
            '#\s*<\/blockquote>\n<blockquote>\s*#',
            // parse two or more white-space(s) at the end of text into a line-break
            '#(\S) {2,}\n#'
        ),
        array(
            '<h1>$1</h1>',
            '<h2>$1</h2>',
            '<hr' . $suffix,
            '\\<strong><em>$3</em></strong>',
            '\\<strong>$2</strong>',
            '\\<em>$2</em>',
            // '\\<del>$2</del>',
            "<ul>\n  <li>$1</li>\n</ul>",
            "<ol>\n  <li>$1</li>\n</ol>",
            "<blockquote>\n  <p>$1</p>\n</blockquote>",
            "\n  ",
            "\n  ",
            "$1<br" . $suffix . "\n"
        ),
        $content);
    // parse table
    $content = preg_replace_callback('#((?:\|[^|]+?\|[^|]+?)+)\|?\n((?:\| *(?:\-+|:\-+|\-+:|:\-+:) *)+\|?)((?:\n(?:\|[^|]+?)+\|?)+)$#m', function($m) {
        $a = explode('|', trim($m[2], '|'));
        $str = "<table border=\"1\">\n";
        $str .= "  <thead>\n";
        $str .= "    <tr>\n";
        foreach(explode('|', trim($m[1], '|')) as $k => $v) {
            $aa = isset($a[$k]) ? ' ' . trim($a[$k]) . ' ' : "";
            if(strpos($aa, ' :') !== false && strpos($aa, ': ') !== false) {
                $a[$k] = ' align="center"';
            } else if(strpos($aa, ' :') !== false) {
                $a[$k] = ' align="left"';
            } else if(strpos($aa, ': ') !== false) {
                $a[$k] = ' align="right"';
            } else if(strpos($aa, ':') === false) {
                $a[$k] = "";
            }
            $str .= "      <th" . $a[$k] . ">" . trim($v) . "</th>\n";
        }
        $str .= "    </tr>\n  </thead>\n";
        $str .= "  <tbody>\n";
        foreach(explode("\n", trim($m[3], "\n")) as $v) {
            $str .= "    <tr>\n";
            foreach(explode('|', trim($v, '|')) as $kk => $vv) {
                $str .= "      <td" . $a[$kk] . ">" . trim($vv) . "</td>\n";
            }
            $str .= "    </tr>\n";
        }
        return $str . "  </tbody>\n</table>\n";
    }, $content);
    // parse new-line to paragraph
    foreach($content = explode("\n\n", $content) as &$line) {
        if(
            $line !== "" // not empty
            && strpos($line, '    ') !== 0 // not a code block
            && strpos($line, "\t") !== 0 // --ibid
            && strpos($line, '<') !== 0 // not a HTML tag
        ) {
            $line = '<p>' . trim($line) . '</p>';
        }
    }
    $content = implode("\n\n", $content);
    // clean-up code block ...
    $content = preg_replace('#<\/code><\/pre>\n<pre><code(>| .*?>)#', "\n", $content);
    // typography (anything outside the HTML tag)
    $content = preg_replace_callback('#(^|<\/?[a-z]+[^>\n]*?>)(.*?)(<\/?[a-z]+[^>\n]*?>|$)#', function($m) {
        $s = str_replace(
            array(
                '&',
                '<',
                '>',
                '---',
                '--',
                '...'
            ),
            array(
                '&amp;',
                '&lt;',
                '&gt;',
                '&mdash;',
                '&ndash;',
                '&hellip;'
            ),
            $m[2]);
        return $m[1] . preg_replace(
                array(
                    '#\'([^\'"]*?)\'#',
                    '#"([^"]*?)"#',
                    '#\b\'#',
                    '#\'\b#',
                    '#&amp;([a-z0-9]+|\#[0-9]+|\#x[a-f0-9]+);#' // restore the encoded html entity
                ),
                array(
                    '&lsquo;$1&rsquo;',
                    '&ldquo;$1&rdquo;',
                    '&rsquo;',
                    '&lsquo;',
                    '&$1;'
                ),
                $s) . $m[3];
    }, $content);
    // un-escape character(s)
    $content = __MDD($content, $x);
    // output the result
    return rtrim($content);
}