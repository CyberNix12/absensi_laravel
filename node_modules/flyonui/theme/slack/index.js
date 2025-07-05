import slack from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addBase, prefix = '' }) => {
  const prefixedslack = addPrefix(slack, prefix);
  addBase({ ...prefixedslack });
};
