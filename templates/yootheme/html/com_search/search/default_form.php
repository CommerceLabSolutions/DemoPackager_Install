<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$lang = Factory::getLanguage();
$limit = $lang->getUpperLimitSearchWord();
$class = function ($html, $class) {
    return preg_replace('/class="[^"]+"/i', "class=\"{$class}\"", $html, 1);
};

$this->lists['ordering'] = $class($this->lists['ordering'], 'uk-select uk-form-width-medium');
$this->lists['limitBox'] = $class($this->pagination->getLimitBox(), 'uk-select uk-form-width-xsmall');

?>

<form id="searchForm" action="<?= Route::_('index.php?option=com_search') ?>" method="post">

    <div class="uk-panel">

        <fieldset class="uk-fieldset">
            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-expand@s">

                    <div class="uk-search uk-search-default uk-width-1-1">
                        <input id="search-searchword" class="uk-search-input" type="text" name="searchword" placeholder="<?= Text::_('COM_SEARCH_SEARCH_KEYWORD') ?>" size="30" maxlength="<?= $limit ?>" value="<?= $this->escape($this->origkeyword) ?>">
                    </div>
                    <input type="hidden" name="task" value="search">

                </div>
                <div class="uk-width-auto@s">

                    <button class="uk-button uk-button-primary uk-width-1-1" name="Search" onclick="this.form.submit()"><?= Text::_('JSEARCH_FILTER_SUBMIT') ?></button>

                </div>
            </div>
        </fieldset>

        <div class="uk-grid-row-small uk-child-width-auto uk-text-small uk-margin" uk-grid>
            <div>

                <?php if ($this->params->get('search_phrases', 1)) : ?>
                <fieldset class="uk-margin uk-fieldset">

                    <div class="uk-grid-collapse uk-child-width-auto" uk-grid>
                        <legend><?= Text::_('COM_SEARCH_FOR') ?></legend>
                        <div>
                            <?= $this->lists['searchphrase'] ?>
                        </div>
                    </div>

                </fieldset>
                <?php endif ?>

            </div>
            <div>

                <?php if ($this->params->get('search_areas', 1)) : ?>
                <fieldset class="uk-margin uk-fieldset">

                    <div class="uk-grid-small uk-child-width-auto" uk-grid>
                        <legend><?= Text::_('COM_SEARCH_SEARCH_ONLY') ?></legend>
                        <div>

                            <div class="uk-grid-small uk-child-width-auto" uk-grid>
                                <?php foreach ($this->searchareas['search'] as $val => $txt) :
                                    $checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
                                ?>
                                <label for="area-<?= $val ?>">
                                    <input type="checkbox" name="areas[]" value="<?= $val ?>" id="area-<?= $val ?>" <?= $checked ?> >
                                    <?= Text::_($txt) ?>
                                </label>
                            <?php endforeach ?>
                            </div>

                        </div>
                    </div>

                </fieldset>
                <?php endif ?>

            </div>
        </div>

    </div>

    <div class="uk-grid-small uk-flex-middle uk-margin-medium" uk-grid>
        <?php if (!empty($this->searchword)) : ?>
        <div class="uk-width-expand@s">
            <div class="uk-h3 "><?= Text::plural('TPL_YOOTHEME_SEARCH_RESULTS', $this->total) ?></div>
        </div>
        <?php endif ?>

        <div class="uk-width-auto@s">

            <div class="uk-grid-small uk-child-width-auto" uk-grid>
                <div>
                    <div><?= $this->lists['ordering'] ?></div>
                </div>
                <div>
                    <div><?= $this->lists['limitBox'] ?></div>
                </div>
            </div>

        </div>

    </div>

</form>
