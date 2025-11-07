<?php
// Tiny Markdown renderer (safe subset).
function md_to_html(string $md): string {
    $md = str_replace(["\r\n","\r"],"\n",$md);
    $md = htmlspecialchars($md, ENT_QUOTES, 'UTF-8'); // escape first

    // headers
    $md = preg_replace('/^\s*#\s+(.+)$/m','<h1>$1</h1>',$md);
    $md = preg_replace('/^\s*##\s+(.+)$/m','<h2>$1</h2>',$md);
    $md = preg_replace('/^\s*###\s+(.+)$/m','<h3>$1</h3>',$md);

    // hr, blockquote, lists
    $md = preg_replace('/^---$/m','<hr>',$md);
    $md = preg_replace('/^&gt;\s?(.+)$/m','<blockquote>$1</blockquote>',$md);
    $md = preg_replace_callback('/((?:^\s*(?:- |\* ).+\n?)+)/m',function($m){
        $items=preg_replace('/^\s*(?:- |\* )(.+)$/m','<li>$1</li>',trim($m[1]));
        return '<ul>'.$items.'</ul>';
    },$md);

    // inline code, bold, italic, links
    $md = preg_replace('/`([^`]+)`/','<code>$1</code>',$md);
    $md = preg_replace('/\*\*([^*]+)\*\*/','<strong>$1</strong>',$md);
    $md = preg_replace('/\*([^*]+)\*/','<em>$1</em>',$md);
    $md = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/',function($m){
        $text=$m[1]; $url=$m[2];
        if(!preg_match('/^(https?:)?\/\//',$url)){ $url=e($url); }
        return '<a href="'.$url.'" target="_blank" rel="noopener noreferrer">'.$text.'</a>';
    },$md);

    // paragraphs
    $parts=preg_split('/\n\n+/',$md);
    foreach($parts as &$p){
      if(!preg_match('/^\s*<(h\d|ul|blockquote|hr)/',$p)){
        $p='<p>'.preg_replace('/\n/','<br>',$p).'</p>';
      }
    }
    return implode("\n",$parts);
}
