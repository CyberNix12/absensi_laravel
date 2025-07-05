import soft from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addBase, prefix = '' }) => {
  const prefixedsoft = addPrefix(soft, prefix);
  addBase({ ...prefixedsoft });
};
