import colors from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addUtilities, prefix = '' }) => {
  const prefixedcolors = addPrefix(colors, prefix);
  addUtilities({ ...prefixedcolors });
};
