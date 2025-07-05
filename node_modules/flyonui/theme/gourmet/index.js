import gourmet from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addBase, prefix = '' }) => {
  const prefixedgourmet = addPrefix(gourmet, prefix);
  addBase({ ...prefixedgourmet });
};
