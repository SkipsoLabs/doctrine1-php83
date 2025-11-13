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
            return $this->varExportForArray($var);
        } else {
            return var_export($var, true);
        }
    }

    /**
    * PHP var_export() with short array syntax (square brackets) indented 2 spaces.
    *
    * @link https://www.php.net/manual/en/function.var-export.php
    *
    * @param array $expression
    * @return string the variable representation
    */
    public function varExportForArray(array $expression): string
    {
        $export = var_export($expression, true);

        // AI-generated: START - Convert array() to [] syntax @dev: Marco Grossi
        // Use regex patterns that preserve string content and only modify structure
        $patterns = [
            "/\barray \(/" => '[',           // array ( -> [
            "/\barray\(/" => '[',             // array( -> [
            "/^([ ]*)\)(,?)$/m" => '$1]$2',  // closing ) at end of line -> ]
            "/\)(,?)(\s*)$/" => ']$1$2',     // closing ) at end of string -> ]
            "/=>[ ]?\n[ ]+\[/" => '=> [',    // format => \n[ -> => [
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);
        // AI-generated: END

        return $export;
    }
}
