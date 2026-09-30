<?php

declare(strict_types=1);

namespace App\View;

use Smarty\Exception as SmartyException;
use Smarty\Smarty;

/**
 * Класс рендеринга шаблонов Smarty
 */
final class SmartyView
{
    /** Настроенный экземпляр Smarty */
    private readonly Smarty $smarty;

    /**
     * Настраивает Smarty для работы с шаблонами
     *
     * @param string $templateDirectory Каталог с шаблонами
     * @param string $compileDirectory Каталог для скомпилированных шаблонов
     * @param string $cacheDirectory Каталог для кеша шаблонов
     */
    public function __construct(
        string $templateDirectory,
        string $compileDirectory,
        string $cacheDirectory,
    ) {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templateDirectory);
        $this->smarty->setCompileDir($compileDirectory);
        $this->smarty->setCacheDir($cacheDirectory);
    }

    /**
     * Рендерит шаблон Smarty с переданными данными
     *
     * @param string $template Имя шаблона
     * @param array<string, mixed> $data Данные шаблона
     * @return string Готовый HTML
     * @throws SmartyException Если шаблон не удалось обработать
     */
    public function render(string $template, array $data = []): string
    {
        $smartyTemplate = $this->smarty->createTemplate($template);
        $smartyTemplate->assign($data);

        return $smartyTemplate->fetch();
    }
}
