<?php
//-------------------------------------------------------
//函式: reply_eagle()
//用途: 回文鷹架
//-------------------------------------------------------

    function reply_eagle($eagle_type){
    //---------------------------------------------------
    //函式: reply_eagle()
    //用途: 回文鷹架
    //---------------------------------------------------
    //$eagle_type   鷹架類型    內容=>1 | 代號=>2
    //---------------------------------------------------

        //-----------------------------------------------
        //鷹架類型 => 內容
        //-----------------------------------------------

            $reply_eagle_content=array(
                trim('●我有看過這本書')  =>array(
                    trim('●(小說/故事)類')  =>array(
                        trim('●(陳述)我想要描述或釐清書中的重要內容')=>array(
                            trim('●(重點與摘要)簡單介紹一本書，整理在書中所讀到的重點與內容')=>array(
                                trim('●這本書的情節在說……                                                       '),
                                trim('●這是……的故事，主角是……，一開始……，後來……                                 '),
                                trim('●這本書的情節背景是……                                                     '),
                                trim('●這本書的主角是......樣的人，他／她……                                     '),
                                trim('●這本書的主角解決問題的方法……                                             '),
                                trim('●這本書的高潮情節……                                                       '),
                                trim('●我認為故事中最重要的句子是......                                         '),
                                trim('●我認為故事中......角色說過......(話)，非常具有意義                       '),
                                trim('●我今天學到了新的東西，我學到……                                           '),
                                trim('●我認為在這......(段落)，最重要的要告訴我們的是......                     '),
                            ),
                            trim('●(內容釐清)對於書中的內容，包含：情節、角色、背景等不清楚的地方，嘗試釐清與說明')=>array(
                                trim('●我對書中情節提到……的地方不太明白，有人知道嗎？                           '),
                                trim('●為什麼……(角色)要……這樣，有人知道嗎？                                     '),
                                trim('●為什麼結局最後會……，有人知道嗎？                                         '),
                                trim('●這本書情節提到……，有什麼相關的書籍嗎？                                   '),
                                trim('●這本書的主角是誰？他像什麼／像誰？                                       '),
                                trim('●這本書的主角面對的問題是什麼？                                           '),
                                trim('●有人知道任何關作者的事情嗎？                                             '),
                            ),
                        ),
                        trim('●(感受)我想要表達讀完書後的感受')=>array(
                            trim('●(感情抒發)看過書籍後，覺得開心、難過、生氣，想要與其他人分享')=>array(
                                trim('●我現在心情很……，因為……(情節、角色)                                       '),
                                trim('●看完這本書的結局，我感覺很……，因為……                                     '),
                                trim('●我對這本書……的部分(情節、角色)很好玩／有趣／感動，因為……                 '),
                                trim('●我最喜歡書中……的角色，因為……，大家最喜歡哪個角色呢？                     '),
                                trim('●我最喜歡書中……的情節內容，因為……，大家最喜歡哪個部分呢？                 '),
                                trim('●我認為......的情節非常好玩／有趣／感動，因為......                       '),
                                trim('●故事中的......角色的個性與我有......的不同                               '),
                                trim('●讀到......時，我感到…...，大家的感覺如何？                               '),
                                trim('●我喜歡這個角色的所做所為，因為（然而）……                                 '),
                                trim('●如果我能成為書中的角色，我要成為......，因為……                           '),
                            ),
                            trim('●(經驗連結)書中故事與自己、親友，或是其他書籍內容描述的有關，產生連結並分享')=>array(
                                trim('●這本書提到……，讓我想到……                                                 '),
                                trim('●這本書故事講的……角色，讓我想到……                                         '),
                                trim('●我以前發生……的情況，跟書裡的情節很類似                                   '),
                                trim('●我曾經也有……的經驗，跟書中提到……的內容一樣                               '),
                            ),
                            trim('●(體會與了解)看過書籍後，對書本內容有新的認識和體悟')=>array(
                                trim('●書中提到的……的內容，讓我體會到……，大家有體會到什麼嗎？                   '),
                                trim('●這本書讓我相信……，因為……，有人跟我想法一樣嗎？                           '),
                                trim('●看完這本書，讓我相信……，因為……                                           '),
                                trim('●這本書提到……的情節，讓我體會到……，因為……                                 '),
                            ),
                        ),
                        trim('●(提問)我想要提出關於這本書的疑問與發現')=>array(
                            trim('●(分析與比較)對於書中的內容提出批判性的想法，並進行分析、檢視與判斷')=>array(
                                trim('●這本書提到……的內容，大家覺得……與……有什麼不一樣的地方嗎？                 '),
                                trim('●看完這本書，我認為……比……更……，有人跟我想法一樣嗎？                       '),
                                trim('●看完這本書，讓我知道……比……更……，因為……                                   '),
                                trim('●這本書提到……的內容，例如……與……不一樣，因為……                             '),
                                trim('●總體來說，這個故事的步調是快還是慢？                                     '),
                                trim('●到目前為止，和其他同樣描寫…...的作品相比，你覺得這個作品如何？           '),
                                trim('●關於書本所提到……的情節，我覺得是……，有沒有人想法跟我一樣啊？             '),
                                trim('●總體來說，這本書的角色是如何發展的？是否說服了你？                       '),
                                trim('●這個故事的敘事方式為何？你能接受它的敘事方式嗎？                         '),
                                trim('●你注意到什麼？舉例來說，這部作品是否流於俗套？                           '),
                                trim('●到目前為止，這本書的內容，是否讓你容易猜出故事的發展？                   '),
                                trim('●我覺得這本書在……的內容，寫的(好/不好)，因為……                            '),
                                trim('●這本書在……的內容，寫的不清楚，應該補充……                                 '),
                                trim('●「……」這段文字說服了我，它呈現出作者的寫作風格                           '),
                                trim('●如果可以，我想改變這個故事......的部分                                   '),
                                trim('●我喜歡／不喜歡這個故事的結局，因為……                                     '),
                                trim('●我喜歡作者……的方式，因為我注意到作者如何……                               '),
                                trim('●我不能理解作者為什麼要……，如果我是作者，我就會……                         '),
                                trim('●我會拿這個作者和……做比較，因為……                                         '),
                                trim('●到目前為止你覺得這本書的寫作風格如何？                                   '),
                                trim('●到目前為止，和作者的其他作品相比，你覺得這個作品如何？                   '),
                            ),
                            trim('●(疑問與發現)根據書籍的內容，我察覺或發現到的想法或知識')=>array(
                                trim('●這本書提到……的內容，透過運用…..，能…….                                   '),
                                trim('●看完這本書後，我學到利用……，能…….                                        '),
                                trim('●這本書提到……，讓我發現……，大家有發現什麼嗎？                             '),
                                trim('●這本書提到……，讓我覺得……，有人跟我想法一樣嗎？                           '),
                                trim('●這本書提到的……的內容，讓我發現……                                         '),
                                trim('●看完這本書，讓我知道……，因為……                                           '),
                                trim('●看完這本書，你覺得……會導致…….嗎？                                        '),
                                trim('●這本書提到……的內容，我們應該要反思什麼？                                 '),
                                trim('●這本書提到……的內容，讓我反思……，因為……                                   '),
                                trim('●這本書提到……的內容，我覺得是…...，大家覺得合理嗎？                       '),
                                trim('●我覺得剛才讀到…...的地方很不合理，因為……                                 '),
                                trim('●這本書的情節內容，在……寫得很(好/不好)，因為……                            '),
                                trim('●我覺得這本書的情節沒有特色，因為……                                       '),
                                trim('●書中提到……的內容，跟我想法不一樣，因為……                                 '),
                            ),
                        ),
                        trim('●(未來)我有一些新點子想要嘗試')=>array(
                            trim('●(聯想與應用)發揮創意，應用所學到的知識')=>array(
                                trim('●除了書中提到運用……能……，我覺得還能夠運用…...，還有什麼可以運用的嗎？     '),
                                trim('●這本書提到關於……的內容，透過…..，能夠…….，沒有人想過或許我們可以……       '),
                                trim('●這個......(內容)對我很重要，因為……，因此我未來能夠實踐……                 '),
                                trim('●這本書提到……的內容，讓我聯想到……，因為……                                 '),
                            ),
                            trim('●(規劃與實現)實際運用知識，並分享自己實際的作法與經驗')=>array(
                                trim('●根據這本的內容，我計畫……，因此我嘗試去做……，結果……                       '),
                                trim('●這本書提到……，因此我實際去……，結果發現…....                              '),
                                trim('●看完這本書後，我學到如何實際去做……，有人要分享實作的經驗嗎？             '),
                            ),
                        ),
                    ),
                    trim('●(非小說/非故事)類')  =>array(
                        trim('●(陳述)我想要描述或釐清書中的重要內容')=>array(
                            trim('●(重點與摘要)簡單介紹一本書，整理在書中所讀到的重點與內容')=>array(
                                trim('●這本書在說跟……有關的知識，例如……                                         '),
                                trim('●我覺得這本書的重點概念是……，因為……                                       '),
                                trim('●這本書提到……的知識，重點是……                                             '),
                                trim('●我今天學到了新的東西，我學到……                                           '),
                                trim('●我認為在這......(段落)，最重要的要告訴我們的是......                     '),
                            ),
                            trim('●(內容釐清)對於書中的內容，包含：情節、角色、背景等不清楚的地方，嘗試釐清與說明')=>array(
                                trim('●我對書中提到……的知識不太明白，因為……，有人知道嗎？                       '),
                                trim('●為什麼書中提到……會這樣，有人知道嗎？                                     '),
                                trim('●我對書中利用……，能……不太清楚，有人知道嗎？                               '),
                            ),
                        ),
                        trim('●(感受)我想要表達讀完書後的感受')=>array(
                            trim('●(感情抒發)看過書籍後，覺得開心、難過、生氣，想要與其他人分享')=>array(
                                trim('●我現在心情很……，因為……(知識、內容)                                       '),
                                trim('●看完這本書的結局，我感覺很……，因為……                                     '),
                                trim('●我對這本書……的部分(知識、內容)好玩／有趣／感動，因為……                   '),
                                trim('●我認為......的內容非常好玩／有趣／感動，因為......                       '),
                                trim('●我最喜歡書中……的知識，因為……，大家最喜歡哪個知識呢？                     '),
                                trim('●我最喜歡書中……的內容，因為……，大家最喜歡哪個部分呢？                     '),
                                trim('●讀到......時，我感到…...，大家的感覺如何？                               '),
                            ),
                            trim('●(經驗連結)書中內容與自己、親友，或是其他書籍內容描述的有關，產生連結並分享')=>array(
                                trim('●這本書講到……的內容，讓我想到……                                           '),
                                trim('●我看過其他……的書，也有類似……的內容                                       '),
                                trim('●我以前也有……的情況，跟書中……的內容一樣                                   '),
                                trim('●我曾經也有……的經驗，跟書中提到……的內容一樣                               '),
                            ),
                            trim('●(體會與了解)看過書籍後，對書本內容有新的認識和體悟')=>array(
                                trim('●書中提到的……的內容，讓我體會到……，大家有體會到什麼嗎？                   '),
                                trim('●這本書讓我相信……，因為……，有人跟我想法一樣嗎？                           '),
                                trim('●這本書提到……的知識，讓我體會到……，因為……                                 '),
                                trim('●看完這本書，讓我相信……，因為……                                           '),
                                trim('●我想對……的相關知識多了解一點，有什麼推薦的書籍嗎？                       '),
                            ),
                        ),
                        trim('●(提問)我想要提出關於這本書的疑問與發現')=>array(
                            trim('●(分析與比較)對於書中的內容提出批判性的想法，並進行分析、檢視與判斷')=>array(
                                trim('●關於書本所提到……的知識，我覺得是……，有沒有人想法跟我一樣啊？             '),
                                trim('●這本書提到……的內容，大家覺得……與……有什麼不一樣的地方嗎？                 '),
                                trim('●看完這本書，我認為……比……更……，有人跟我想法一樣嗎？                       '),
                                trim('●看完這本書，讓我知道……比……更……，因為……                                   '),
                                trim('●這本書提到……的內容，例如……與……不一樣，因為……                             '),
                                trim('●這本書的內容，在……寫得很(好/不好)，因為……                                '),
                                trim('●我覺得這本書的內容沒有特色，因為……                                       '),
                                trim('●書中提到……的內容，跟我想法不一樣，因為……                                 '),
                                trim('●我覺得這本書在……的內容，寫的(好/不好)，因為……                            '),
                                trim('●這本書在……的內容，寫的不清楚，應該補充……                                 '),
                                trim('●我認為這本書在有關......的知識上，見解非常的精闢／有道理                 '),
                                trim('●我喜歡作者……的方式，因為我注意到作者如何……                               '),
                                trim('●我不能理解作者為什麼要……，如果我是作者，我就會……                         '),
                                trim('●我會拿這個作者和……做比較，因為……                                         '),
                                trim('●到目前為止，和作者的其他作品相比，你覺得這個作品如何？                   '),
                            ),
                            trim('●(疑問與發現)根據書籍的內容，我察覺或發現到的想法或知識')=>array(
                                trim('●這本書提到……的內容，透過運用…..，能…….                                   '),
                                trim('●看完這本書後，我學到利用……，能…….                                        '),
                                trim('●這本書提到……，讓我發現……，大家有發現什麼嗎？                             '),
                                trim('●這本書提到……，讓我覺得……，有人跟我想法一樣嗎？                           '),
                                trim('●這本書提到的……的內容，讓我發現……                                         '),
                                trim('●看完這本書，讓我知道……，因為……                                           '),
                                trim('●看完這本書，你覺得……會導致…….嗎？                                        '),
                                trim('●這本書提到……的內容，我們應該要反思什麼？                                 '),
                                trim('●這本書提到……的內容，讓我反思……，因為……                                   '),
                                trim('●這本書提到……的內容，我覺得是…...，大家覺得合理嗎？                       '),
                                trim('●我覺得剛才讀到…...的地方很不合理，因為……                                 '),
                            ),
                        ),
                        trim('●(未來)我有一些新點子想要嘗試')=>array(
                            trim('●(聯想與應用)發揮創意，應用所學到的知識')=>array(
                                trim('●除了書中提到運用……能……，我覺得還能夠運用…...，還有什麼可以運用的嗎？     '),
                                trim('●這本書提到關於……的內容，透過…..，能夠…….，沒有人想過或許我們可以……       '),
                                trim('●這個......(知識)對我很重要，因為……，因此我未來能夠實踐……                 '),
                                trim('●這本書提到……的內容，讓我聯想到……，因為……                                 '),
                            ),
                            trim('●(規劃與實現)實際運用知識，並分享自己實際的作法與經驗')=>array(
                                trim('●根據這本的內容，我計畫……，因此我嘗試去做……，結果……                       '),
                                trim('●這本書提到……，因此我實際去……，結果發現…....                              '),
                                trim('●看完這本書後，我學到如何實際去做……，有人要分享實作的經驗嗎？             '),
                            ),
                        ),
                    ),
                ),
                trim('●我沒有看過這本書')  =>array(
                    trim('●(小說/故事)類')  =>array(
                        trim('●(陳述)我想要釐清書中的重要內容')  =>array(
                            trim('●其他                                                             '),
                            trim('●我覺得你說的很好，但我還想補充……                                 '),
                            trim('●在……的部分，我跟你的想法不一樣，因為……                           '),
                            trim('●這本書的主角面對的問題是什麼？                                   '),
                            trim('●有人知道任何關作者的事情嗎？                                     '),
                            trim('●這本書的結局是什麼？                                             '),
                            trim('●這本書的主角解決問題的方法是什麼？                               '),
                            trim('●這本書情節提到……，有什麼相關的書籍嗎？                           '),
                        ),
                        trim('●(提問)我想要提出關於這本書的疑問')  =>array(
                            trim('●看完這本書，你發現到/學到了什麼？                                '),
                            trim('●總體來說，這本書的角色是如何發展的？是否說服了你？               '),
                            trim('●到目前為止，和其他同樣描寫…...的作品相比，你覺得這個作品如何？   '),
                            trim('●這個故事的敘事方式為何？你能接受它的敘事方式嗎？                 '),
                            trim('●到目前為止，這本書的內容，是否讓你容易猜出故事的發展？           '),
                            trim('●到目前為止你覺得這本書的寫作風格如何？                           '),
                            trim('●到目前為止，和作者的其他作品相比，你覺得這個作品如何？           '),
                        ),
                    ),
                    trim('●(非小說/非故事)類')  =>array(
                        trim('●(陳述)我想要釐清書中的重要內容')  =>array(
                            trim('●我覺得你說的很好，但我還想補充……                                 '),
                            trim('●在……的部分，我跟你的想法不一樣，因為……                           '),
                            trim('●其他                                                             '),
                            trim('●這本書的重點概念是什麼？                                         '),
                            trim('●這本書在說跟什麼有關的知識？                                     '),
                            trim('●我想對……的相關知識多了解一點，有什麼推薦的書籍嗎？               '),
                            trim('●我看過其他……的書，也有類似……的內容                               '),
                        ),
                        trim('●(提問)我想要提出關於這本書的疑問')  =>array(
                            trim('●看完這本書，你發現到/學到了什麼？                                '),
                            trim('●到目前為止，和作者的其他作品相比，你覺得這個作品如何？           '),
                            trim('●你覺得這本書的內容，有沒有不合理的地方？                         '),
                            trim('●書中……的概念，在你的生活中有經歷過嗎？為什麼？                   '),
                        ),
                    ),
                ),
            );

        //-----------------------------------------------
        //鷹架類型 => 代號
        //-----------------------------------------------

            $reply_eagle_code=array(
                trim('●我有看過這本書')  =>array(
                    trim('●(小說/故事)類')  =>array(
                        trim('●(陳述)我想要描述或釐清書中的重要內容')=>array(
                            trim('●(重點與摘要)簡單介紹一本書，整理在書中所讀到的重點與內容')=>array(
                                trim(26),
                                trim(27),
                                trim(28),
                                trim(29),
                                trim(30),
                                trim(31),
                                trim(32),
                                trim(33),
                                trim(34),
                                trim(35),
                            ),
                            trim('●(內容釐清)對於書中的內容，包含：情節、角色、背景等不清楚的地方，嘗試釐清與說明')=>array(
                                trim(36),
                                trim(37),
                                trim(38),
                                trim(39),
                                trim(40),
                                trim(41),
                                trim(42),
                            ),
                        ),
                        trim('●(感受)我想要表達讀完書後的感受')=>array(
                            trim('●(感情抒發)看過書籍後，覺得開心、難過、生氣，想要與其他人分享')=>array(
                                trim(43),
                                trim(44),
                                trim(45),
                                trim(46),
                                trim(47),
                                trim(48),
                                trim(49),
                                trim(50),
                                trim(51),
                                trim(52),
                            ),
                            trim('●(經驗連結)書中故事與自己、親友，或是其他書籍內容描述的有關，產生連結並分享')=>array(
                                trim(53),
                                trim(54),
                                trim(55),
                                trim(56),
                            ),
                            trim('●(體會與了解)看過書籍後，對書本內容有新的認識和體悟')=>array(
                                trim(57),
                                trim(58),
                                trim(59),
                                trim(60),
                            ),
                        ),
                        trim('●(提問)我想要提出關於這本書的疑問與發現')=>array(
                            trim('●(分析與比較)對於書中的內容提出批判性的想法，並進行分析、檢視與判斷')=>array(
                                trim(61),
                                trim(62),
                                trim(63),
                                trim(64),
                                trim(65),
                                trim(66),
                                trim(67),
                                trim(68),
                                trim(69),
                                trim(70),
                                trim(71),
                                trim(72),
                                trim(73),
                                trim(74),
                                trim(75),
                                trim(76),
                                trim(77),
                                trim(78),
                                trim(79),
                                trim(80),
                                trim(81),
                            ),
                            trim('●(疑問與發現)根據書籍的內容，我察覺或發現到的想法或知識')=>array(
                                trim(82),
                                trim(83),
                                trim(84),
                                trim(85),
                                trim(86),
                                trim(87),
                                trim(88),
                                trim(89),
                                trim(90),
                                trim(91),
                                trim(92),
                                trim(93),
                                trim(94),
                                trim(95),
                            ),
                        ),
                        trim('●(未來)我有一些新點子想要嘗試')=>array(
                            trim('●(聯想與應用)發揮創意，應用所學到的知識')=>array(
                                trim(96),
                                trim(97),
                                trim(98),
                                trim(99),
                            ),
                            trim('●(規劃與實現)實際運用知識，並分享自己實際的作法與經驗')=>array(
                                trim(100),
                                trim(101),
                                trim(102),
                            ),
                        ),
                    ),
                    trim('●(非小說/非故事)類')  =>array(
                        trim('●(陳述)我想要描述或釐清書中的重要內容')=>array(
                            trim('●(重點與摘要)簡單介紹一本書，整理在書中所讀到的重點與內容')=>array(
                                trim(103),
                                trim(104),
                                trim(105),
                                trim(106),
                                trim(107),
                            ),
                            trim('●(內容釐清)對於書中的內容，包含：情節、角色、背景等不清楚的地方，嘗試釐清與說明')=>array(
                                trim(108),
                                trim(109),
                                trim(110),
                            ),
                        ),
                        trim('●(感受)我想要表達讀完書後的感受')=>array(
                            trim('●(感情抒發)看過書籍後，覺得開心、難過、生氣，想要與其他人分享')=>array(
                                trim(111),
                                trim(112),
                                trim(113),
                                trim(114),
                                trim(115),
                                trim(116),
                                trim(117),
                            ),
                            trim('●(經驗連結)書中內容與自己、親友，或是其他書籍內容描述的有關，產生連結並分享')=>array(
                                trim(118),
                                trim(119),
                                trim(120),
                                trim(121),
                            ),
                            trim('●(體會與了解)看過書籍後，對書本內容有新的認識和體悟')=>array(
                                trim(122),
                                trim(123),
                                trim(124),
                                trim(125),
                                trim(126),
                            ),
                        ),
                        trim('●(提問)我想要提出關於這本書的疑問與發現')=>array(
                            trim('●(分析與比較)對於書中的內容提出批判性的想法，並進行分析、檢視與判斷')=>array(
                                trim(127),
                                trim(128),
                                trim(129),
                                trim(130),
                                trim(131),
                                trim(132),
                                trim(133),
                                trim(134),
                                trim(135),
                                trim(136),
                                trim(137),
                                trim(138),
                                trim(139),
                                trim(140),
                                trim(141),
                            ),
                            trim('●(疑問與發現)根據書籍的內容，我察覺或發現到的想法或知識')=>array(
                                trim(142),
                                trim(143),
                                trim(144),
                                trim(145),
                                trim(146),
                                trim(147),
                                trim(148),
                                trim(149),
                                trim(150),
                                trim(151),
                                trim(152),
                            ),
                        ),
                        trim('●(未來)我有一些新點子想要嘗試')=>array(
                            trim('●(聯想與應用)發揮創意，應用所學到的知識')=>array(
                                trim(153),
                                trim(154),
                                trim(155),
                                trim(156),
                            ),
                            trim('●(規劃與實現)實際運用知識，並分享自己實際的作法與經驗')=>array(
                                trim(157),
                                trim(158),
                                trim(159),
                            ),
                        ),
                    ),
                ),
                trim('●我沒有看過這本書')  =>array(
                    trim('●(小說/故事)類')  =>array(
                        trim('●(陳述)我想要釐清書中的重要內容')  =>array(
                            trim(0),
                            trim(1),
                            trim(2),
                            trim(3),
                            trim(4),
                            trim(5),
                            trim(6),
                            trim(7),
                        ),
                        trim('●(提問)我想要提出關於這本書的疑問')  =>array(
                            trim(8),
                            trim(9),
                            trim(10),
                            trim(11),
                            trim(12),
                            trim(13),
                            trim(14),
                        ),
                    ),
                    trim('●(非小說/非故事)類')  =>array(
                        trim('●(陳述)我想要釐清書中的重要內容')  =>array(
                            trim(15),
                            trim(16),
                            trim(17),
                            trim(18),
                            trim(19),
                            trim(20),
                            trim(21),
                        ),
                        trim('●(提問)我想要提出關於這本書的疑問')  =>array(
                            trim(22),
                            trim(23),
                            trim(24),
                            trim(25),
                        ),
                    ),
                ),
            );

        //-----------------------------------------------
        //回傳
        //-----------------------------------------------

            switch((int)($eagle_type)){
                case 1:
                        return $reply_eagle_content;
                    break;

                case 2:
                        return $reply_eagle_code;
                    break;

                default:
                        die('REPLY_EAGLE(): eagle_type err!');
                    break;

            }
    }
?>