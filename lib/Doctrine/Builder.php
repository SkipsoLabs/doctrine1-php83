<?php
/*
 *  $Id: Builder.php 4593 2008-06-29 03:24:50Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Base class for any code builders/generators for Doctrine
 *
 * @package     Doctrine
 * @subpackage  Builder
 * @link        www.doctrine-project.org
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @since       1.0
 * @version     $Revision: 4593 $
 * @author      Jonathan H. Wage <jwage@mac.com>
 */
class Doctrine_Builder
{
    /**
     * Special function for var_export()
     * The normal code which is returned is malformed and does not follow Doctrine standards
     * So we do some string replacing to clean it up
     *
     * @param string $var
     * @return void
     */
    public function varExport($var)
    {
        if (is_array($var)) {
            $export = $this->varExportForArray($var);
        } else {
            $export = var_export($var, true);
        }
        $export = str_replace("\n", PHP_EOL . str_repeat(' ', 50), $export);
        $export = str_replace('  ', ' ', $export);
        // Safety net: ensure any remaining array() syntax is converted to []
        $export = str_replace('array (', '[', $export);
        $export = str_replace('array(', '[', $export);
        $export = str_replace(',]', ']', $export);

        return $export;
    }

    /**
    * PHP var_export() with short array syntax (square brackets) indented 2 spaces.
    *
    * NOTE: The only issue is when a string value has `=>\n[`, it will get converted to `=> [`
    * @link https://www.php.net/manual/en/function.var-export.php
    *
    * @param array $expression
    * @return string the variable representation
    */
    public function varExportForArray(array $expression): string
    {
        $export = var_export($expression, true);
        $patterns = [
            "/array \(/" => '[',
            "/array\(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/\)(,?)(\s*)$/" => ']$1$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);
        return $export;
    }
}
