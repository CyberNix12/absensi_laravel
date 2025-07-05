import advanceSelect from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addComponents, prefix = '' }) => {
  const prefixedadvanceSelect = addPrefix(advanceSelect, prefix);
  addComponents({ ...prefixedadvanceSelect });
};
