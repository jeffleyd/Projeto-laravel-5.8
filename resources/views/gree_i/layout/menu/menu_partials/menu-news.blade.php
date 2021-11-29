                
                <li id="mNews" class="dropdown nav-item" <?php if (hasPermManager(5)) { ?>data-menu="dropdown"<?php } ?>><a class="<?php if (hasPermManager(5)) { ?>dropdown-toggle<?php } ?> nav-link" href="/news"><i class="menu-livicon" data-icon="notebook"></i><span>{{ __('layout_i.menu_news') }}</span></a>
                    <?php if (hasPermManager(5)) { ?>
                    <ul class="dropdown-menu">
                        <li id="mBlog" class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item align-items-center dropdown-toggle" href="#" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_news_publish') }}</a>
                            <ul class="dropdown-menu">
                                <li id="mBlogNew" data-menu=""><a class="dropdown-item align-items-center" href="/blog/edit/0" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_news_post') }}</a>
                                </li>
                                <li id="mBlogAll" data-menu=""><a class="dropdown-item align-items-center" href="/blog/view/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_news_posts') }}</a>
                                </li>
                            </ul>
                        </li>
                        <?php if (hasPermApprov(5)) { ?>
                        <li id="mAuthorAll" data-menu=""><a class="dropdown-item align-items-center" href="/blog/author/all" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_news_author') }}</a>
                        </li>
                        <li id="mListTransmission" data-menu=""><a class="dropdown-item align-items-center" href="/blog/transmission" data-toggle="dropdown"><i class="bx bx-right-arrow-alt"></i>{{ __('layout_i.menu_news_list_transmission') }}</a>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </li>