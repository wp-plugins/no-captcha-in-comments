<?php
$ncc_threshold=0;

function ncc_classifier($ncc_comment_data){
    global $ncc_threshold;
$ncc_spam_words = array("asics","backup","benefit","blog","bord","campaign","coup","discount","downtime","emerg","entr","free","help","htm","http","index","info","key","kino","kit","lacoste","lauren","link","list","load","loan","market","misc","occh","online","outlet","porn","post","prof","review","sale","scarpe","serv","site","spade","store","strat","temp","theme","title","uptime","url","util","weblog","shut","acne","adsense","amateur","anonymous","com","org","authentic","b2b","beneficial","broker","buy","co","in","cash","casino","cellphone","cheap","cheapest","cheat","cigar","cigarettes","cigars","click","coins","comfort","cosmetics","costume","coupon","data recovery","download","ebay","entries","fantasia","fashion","finance","adult","sale","fuck","fucked","fucker","fucking","fucks","fund","gambling","gaming","gifts","handbags","herbal","income","insurance","iphone","jacket","jackets","jerking","link","loan","lottery","lover","malware","mbts","model","nude","order","pictures","poker","premium","price","promo","promotions","real estate","service","sexy","store","tax","training","webpage","website","webhost","weddingdress","wedding photo","wholesale","xchange","fake","html?","html","gay","affordable","anonymous","baby","bazar","bustier","chat","commodity","customercare","download","drug","filmy","hot","hotdeal","install","lovequote","update","upload","visit","wp_admin","wp_content","wp_image","wp_include","wp_list","wp_site","wpcontent","www","xml","addidas","advantages","age","amzon","co","antivirus","credit","balance","bellyfat","brand","candycrush","cash4gold","cash4silver","cashadvance","cashforgold","cashforsilver","cashgenerator","cashloan","cashout");
$ncc_comment_array = explode(" ",$ncc_comment_data);
$ncc_comment_len=count($ncc_comment_array);
if($ncc_comment_len <=2 )
{
    return 1;
}
$ncc_spam_array_lower=explode(" ",strtolower(implode(" ",$ncc_spam_words)));
foreach($ncc_comment_array as $ncc_each_word)
{

    if(in_array($ncc_each_word, $ncc_spam_array_lower))
    {
            $ncc_threshold++;

    }
}

$ncc_spam_prob=$ncc_threshold/$ncc_comment_len;
return $ncc_spam_prob;
}
