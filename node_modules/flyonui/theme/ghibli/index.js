import ghibli from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addBase, prefix = '' }) => {
  const prefixedghibli = addPrefix(ghibli, prefix);
  addBase({ ...prefixedghibli });
};
